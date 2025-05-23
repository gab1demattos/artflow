// JS for tab switching on the activity page
document.addEventListener("DOMContentLoaded", function () {
	const tabs = document.querySelectorAll(".activity-tab");
	const contents = document.querySelectorAll(".activity-tab-content");
	tabs.forEach((tab) => {
		tab.addEventListener("click", function () {
			tabs.forEach((t) => t.classList.remove("active"));
			contents.forEach((c) => c.classList.remove("active"));
			tab.classList.add("active");
			document.getElementById(tab.dataset.tab).classList.add("active");
		});
	});

	// Fetch and render orders dynamically
	fetch("/actions/activity/get-orders.php")
		.then((res) => res.json())
		.then((data) => {
			if (!data.success) return;
			// Render Your Orders
			const yourOrders = document.getElementById("your-orders");
			yourOrders.innerHTML = "";
			if (data.yourOrders.length === 0) {
				yourOrders.innerHTML = '<div class="no-orders">No orders found.</div>';
			} else {
				// Sort: in progress first, then completed
				const sortedOrders = data.yourOrders.slice().sort((a, b) => {
					if (a.status === b.status) return 0;
					if (a.status === "in progress") return -1;
					if (b.status === "in progress") return 1;
					return 0;
				});
				sortedOrders.forEach((order) => {
					const isCompleted = order.status === "completed";
					yourOrders.innerHTML += `
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-title">${order.title}</span>
                            <span class="order-status ${
															isCompleted ? "completed" : "in-progress"
														}">${
						isCompleted ? "Completed" : "In Progress"
					}</span>
                        </div>
                        <div class="order-details">
                            <div><strong>Seller:</strong> ${
															order.seller_name
														} (@${order.seller_username})</div>
                            <div><strong>Delivery:</strong> ${
															order.delivery_time
														} days</div>
                            <div><strong>Requirements:</strong> ${
															order.requirements
														}</div>
                            <div><strong>Total:</strong> ${order.price}€</div>
                            <div><strong>Date:</strong> ${
															order.date ? order.date.split(" ")[0] : ""
														}</div>
                        </div>
                        ${
													isCompleted
														? `<button class="rate-it-btn" data-service-id="${order.service_id}" data-service-title="${order.title}">Rate it!</button>`
														: ""
												}
                    </div>`;
				});

				// Attach event listeners to the Rate it buttons
				attachRateItListeners();
			}
			// Render Orders From Others
			const ordersFromOthers = document.getElementById("orders-from-others");
			ordersFromOthers.innerHTML = "";
			if (data.ordersFromOthers.length === 0) {
				ordersFromOthers.innerHTML =
					'<div class="no-orders">No orders found.</div>';
			} else {
				// Sort: not delivered (in progress) first, then delivered (completed)
				const sortedOrders = data.ordersFromOthers.slice().sort((a, b) => {
					if (a.status === b.status) return 0;
					if (a.status === "in progress") return -1;
					if (b.status === "in progress") return 1;
					return 0;
				});
				sortedOrders.forEach((order) => {
					const delivered = order.status === "completed";
					ordersFromOthers.innerHTML += `
                    <div class="order-card" data-order-id="${order.id}">
                        <div class="order-header">
                            <span class="order-title">${order.title}</span>
                            <span class="order-status ${
															delivered ? "delivered" : "not-delivered"
														}">${
						delivered ? "Delivered" : "Not Delivered"
					}</span>
                        </div>
                        <div class="order-details">
                            <div><strong>Buyer:</strong> ${
															order.buyer_name
														} (@${order.buyer_username})</div>
                            <div><strong>Delivery:</strong> ${
															order.delivery_time
														} days</div>
                            <div><strong>Requirements:</strong> ${
															order.requirements
														}</div>
                            <div><strong>Total:</strong> ${order.price}€</div>
                            <div><strong>Date:</strong> ${
															order.date ? order.date.split(" ")[0] : ""
														}</div>
                        </div>
                        ${
													!delivered
														? '<button class="mark-delivered-btn">Mark as Delivered</button>'
														: ""
												}
                    </div>`;
				});
			}
			// Re-attach mark delivered listeners
			document.querySelectorAll(".mark-delivered-btn").forEach(function (btn) {
				btn.addEventListener("click", function () {
					const card = btn.closest(".order-card");
					if (!card) return;
					const orderId = card.getAttribute("data-order-id");
					btn.disabled = true;
					fetch("/actions/activity/mark-delivered.php", {
						method: "POST",
						headers: { "Content-Type": "application/x-www-form-urlencoded" },
						body: `order_id=${encodeURIComponent(orderId)}`,
					})
						.then((res) => res.json())
						.then((result) => {
							if (result.success) {
								const status = card.querySelector(".order-status");
								if (status) {
									status.textContent = "Delivered";
									status.classList.remove("not-delivered");
									status.classList.add("delivered");
								}
								btn.remove();
							} else {
								btn.disabled = false;
								alert(result.error || "Failed to mark as delivered.");
							}
						})
						.catch(() => {
							btn.disabled = false;
							alert("Failed to mark as delivered.");
						});
				});
			});
		});

	// Function to attach event listeners to all Rate it buttons
	function attachRateItListeners() {
		document.querySelectorAll(".rate-it-btn").forEach((button) => {
			button.addEventListener("click", function () {
				const serviceId = this.getAttribute("data-service-id");
				const serviceTitle = this.getAttribute("data-service-title");

				console.log("Rate It button clicked:", serviceId, serviceTitle);

				// Check if modal already exists
				let rateItModal = document.getElementById("rate-it-modal-overlay");

				if (!rateItModal) {
					// Create the modal dynamically
					rateItModal = document.createElement("div");
					rateItModal.id = "rate-it-modal-overlay";
					rateItModal.className = "modal-overlay";

					rateItModal.innerHTML = `
						<div id="rate-it-modal" class="modal">
							<div class="modal-content">
								<h2>Rate Service</h2>
								<div class="service-title">
									<h3>${serviceTitle}</h3>
								</div>
								<form id="rate-it-form" action="/actions/submit-review.php" method="POST">
									<input type="hidden" name="service_id" value="${serviceId}">
									<input type="hidden" name="rating" id="rating-value" value="0">
									
									<div class="form-group">
										<label for="review-text">Write your review:</label>
										<textarea id="review-text" name="review_text" placeholder="Share your experience with this service..." required></textarea>
									</div>
									
									<div class="rating-section">
										<div class="rating-container">
											<label>Rating:</label>
											<div class="stars-container">
												<div class="stars">
													<i class="star-icon" data-value="1.0">★</i>
													<i class="star-icon" data-value="2.0">★</i>
													<i class="star-icon" data-value="3.0">★</i>
													<i class="star-icon" data-value="4.0">★</i>
													<i class="star-icon" data-value="5.0">★</i>
												</div>
											</div>
											<div class="rating-display">
												<span id="rating-text">0.0</span>/5
											</div>
										</div>
									</div>
									
									<div class="public-review">
										<label for="make-public">
											<input type="checkbox" id="make-public" name="make_public" value="1">
											Make my requirements public
										</label>
									</div>
									
									<div class="button-container">
										<button type="button" id="close-rate-it" class="button outline yellow">Cancel</button>
										<button type="submit" id="submit-rating" class="button filled yellow">Submit Review</button>
									</div>
								</form>
							</div>
						</div>
					`;

					document.body.appendChild(rateItModal);

					// Setup stars functionality
					setupRatingStars();

					// Close modal handlers
					const closeButton = document.getElementById("close-rate-it");
					if (closeButton) {
						closeButton.addEventListener("click", function () {
							rateItModal.style.display = "none";
						});
					}

					// Close when clicking outside content
					rateItModal.addEventListener("click", function (event) {
						if (event.target === rateItModal) {
							rateItModal.style.display = "none";
						}
					});

					// Form validation
					const rateForm = document.getElementById("rate-it-form");
					if (rateForm) {
						rateForm.addEventListener("submit", function (event) {
							event.preventDefault(); // Prevent the default form submission

							const currentRating = parseFloat(
								document.getElementById("rating-value").value
							);
							if (currentRating === 0) {
								alert("Please select a rating before submitting");
								return false;
							}

							// Get form data
							const serviceId = document.querySelector(
								"input[name='service_id']"
							).value;
							console.log("Service ID being submitted:", serviceId); // Debug log

							if (!serviceId || serviceId === "0" || serviceId === 0) {
								alert("Error: Invalid service ID. Please try again.");
								return false;
							}

							const rating = document.getElementById("rating-value").value;
							const reviewText = document.getElementById("review-text").value;
							const makePublic = document.getElementById("make-public").checked
								? "1"
								: "0";

							// Create URL-encoded form data
							const formData = new URLSearchParams();
							formData.append("service_id", serviceId);
							formData.append("rating", rating);
							formData.append("review_text", reviewText);
							formData.append("make_public", makePublic);

							console.log("Submitting form data:", formData.toString()); // Debug log

							// Submit the review via fetch API
							fetch("/actions/submit-review.php", {
								method: "POST",
								headers: {
									"Content-Type": "application/x-www-form-urlencoded",
								},
								body: formData.toString(),
							})
								.then((response) =>
									response.json().catch(() => response.text())
								)
								.then((result) => {
									// Check if result is a string (HTML) or an object
									if (typeof result === "string") {
										// Success - close the modal and show alert
										rateItModal.style.display = "none";
										alert("Review submitted successfully!");

										// Redirect to the service page
										window.location.href = "/pages/service.php?id=" + serviceId;
									} else if (result.success) {
										// Success with JSON response
										rateItModal.style.display = "none";
										alert("Review submitted successfully!");

										// Redirect to the service page
										window.location.href = "/pages/service.php?id=" + serviceId;
									} else {
										// Error
										alert(
											result.error ||
												"An error occurred while submitting your review."
										);
									}
								})
								.catch((error) => {
									console.error("Error submitting review:", error);
									alert(
										"An error occurred while submitting your review. Please try again."
									);
								});
						});
					}
				} else {
					// Modal exists, update its content
					const serviceIdInput = rateItModal.querySelector(
						"input[name='service_id']"
					);
					if (serviceIdInput) {
						serviceIdInput.value = serviceId;
					}

					const titleElement = rateItModal.querySelector(".service-title h3");
					if (titleElement) {
						titleElement.textContent = serviceTitle;
					}

					// Reset the form
					const rateForm = document.getElementById("rate-it-form");
					if (rateForm) {
						rateForm.reset();
						document.getElementById("rating-value").value = "0";
						document.getElementById("rating-text").textContent = "0.0";
						document.querySelectorAll(".star-icon").forEach((star) => {
							star.classList.remove("active");
							star.classList.remove("half");
							star.textContent = "★";
						});
					}
				}

				// Display the modal
				rateItModal.style.display = "flex";
			});
		});
	}

	// Function to setup rating star functionality
	function setupRatingStars() {
		const stars = document.querySelectorAll(".star-icon");
		const ratingValue = document.getElementById("rating-value");
		const ratingText = document.getElementById("rating-text");
		let currentRating = 0;

		// Function to update stars display
		function updateStarDisplay(rating) {
			stars.forEach((star) => {
				const starValue = parseFloat(star.getAttribute("data-value"));

				// Full star
				if (starValue <= rating) {
					star.classList.add("active");
					star.classList.remove("half");
					star.textContent = "★";
				}
				// Half star - if rating is X.5 and this is the next star
				else if (starValue - 0.5 === rating) {
					star.classList.add("active", "half");
					star.textContent = "⯪";
				}
				// Empty star
				else {
					star.classList.remove("active", "half");
					star.textContent = "★";
				}
			});

			// Update the rating text and hidden value
			ratingText.textContent = rating.toFixed(1);
			ratingValue.value = rating;
		}

		// Handle click events for full and half stars
		stars.forEach((star, index) => {
			const starContainer = star.parentElement;
			const starRect = star.getBoundingClientRect();
			const starWidth = starRect.width;

			// Show preview on hover
			star.addEventListener("mousemove", function (e) {
				const rect = this.getBoundingClientRect();
				const x = e.clientX - rect.left; // x position within the element
				const starValue = parseFloat(this.getAttribute("data-value"));

				if (x < rect.width / 2) {
					// Left half of the star - show half star
					updateStarDisplay(starValue - 0.5);
				} else {
					// Right half of the star - show full star
					updateStarDisplay(starValue);
				}
			});

			// Set rating on click
			star.addEventListener("click", function (e) {
				const rect = this.getBoundingClientRect();
				const x = e.clientX - rect.left;
				const starValue = parseFloat(this.getAttribute("data-value"));

				if (x < rect.width / 2) {
					// Left half of the star clicked
					currentRating = starValue - 0.5;
				} else {
					// Right half or whole star clicked
					currentRating = starValue;
				}

				updateStarDisplay(currentRating);
			});
		});

		// Reset to current rating when mouse leaves the star container
		const starsContainer = document.querySelector(".stars-container");
		if (starsContainer) {
			starsContainer.addEventListener("mouseleave", function () {
				updateStarDisplay(currentRating);
			});
		}
	}
});
