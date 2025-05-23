/**
 * Rate-it modal functionality
 * Handles the star rating system and form submission
 */

// Global variables
let rateItModal = null;
let rateItForm = null;
let ratingValue = null;
let ratingText = null;
let stars = null;
let currentRating = 0;

// Initialize on DOM content loaded
document.addEventListener("DOMContentLoaded", function () {
	// Get modal elements
	rateItModal = document.getElementById("rate-it-modal-overlay");

	// If modal isn't found, it might not be loaded yet or at all
	if (!rateItModal) {
		console.warn("Rate-it modal not found on page load");
		return;
	}

	rateItForm = document.getElementById("rate-it-form");
	ratingValue = document.getElementById("rating-value");
	ratingText = document.getElementById("rating-text");
	const closeButton = document.getElementById("close-rate-it");
	stars = document.querySelectorAll(".star-icon");
	const starsContainer = document.querySelector(".stars-container");

	console.log("Rate-it modal initialized");

	// Set up star rating behavior
	stars.forEach((star) => {
		// Show preview on hover
		star.addEventListener("mouseover", function () {
			const hoverRating = parseFloat(this.getAttribute("data-value"));
			updateStarDisplay(hoverRating);
		});

		// Set rating on click
		star.addEventListener("click", function () {
			currentRating = parseFloat(this.getAttribute("data-value"));
			updateStarDisplay(currentRating);
		});
	});

	// Reset to current rating when mouse leaves the star container
	if (starsContainer) {
		starsContainer.addEventListener("mouseleave", function () {
			updateStarDisplay(currentRating);
		});
	}

	// Close modal
	if (closeButton) {
		closeButton.addEventListener("click", function () {
			closeRateItModal();
		});
	}

	// Close when clicking outside the modal content
	rateItModal.addEventListener("click", function (event) {
		if (event.target === rateItModal) {
			closeRateItModal();
		}
	});

	// Form validation before submission
	if (rateItForm) {
		rateItForm.addEventListener("submit", function (event) {
			if (currentRating === 0) {
				event.preventDefault();
				alert("Please select a rating before submitting");
				return false;
			}
			// Form is valid, will submit
		});
	}
});

// Function to set the star ratings visually
function updateStarDisplay(rating) {
	if (!stars) {
		stars = document.querySelectorAll(".star-icon");
		if (!stars.length) {
			console.error("Star icons not found");
			return;
		}
	}

	stars.forEach((star) => {
		const starValue = parseFloat(star.getAttribute("data-value"));
		if (starValue <= rating) {
			star.classList.add("active");
		} else {
			star.classList.remove("active");
		}
	});

	// Update the rating text and hidden value
	if (ratingText) ratingText.textContent = rating.toFixed(1);
	if (ratingValue) ratingValue.value = rating;
}

// Function to close the modal
function closeRateItModal() {
	if (rateItModal) {
		rateItModal.style.display = "none";
	}
}

// Function to open the modal - defined globally for external access
window.openRateItModal = function (serviceId, serviceTitle) {
	console.log("Opening rate-it modal for:", serviceId, serviceTitle);

	// Make sure we have the latest reference to the modal
	if (!rateItModal) {
		rateItModal = document.getElementById("rate-it-modal-overlay");
	}

	if (!rateItForm) {
		rateItForm = document.getElementById("rate-it-form");
	}

	// Check if modal elements exist
	if (!rateItModal || !rateItForm) {
		console.error(
			"Rate-it modal elements not found. Modal may not be included in the page."
		);
		return;
	}

	// Reset the form and rating
	rateItForm.reset();
	currentRating = 0;

	// Set the service ID and title if provided
	if (serviceId) {
		const serviceIdInput = document.querySelector('input[name="service_id"]');
		if (serviceIdInput) {
			serviceIdInput.value = serviceId;
		}
	}

	if (serviceTitle) {
		const titleElement = document.querySelector(".service-title h3");
		if (titleElement) {
			titleElement.textContent = serviceTitle;
		}
	}

	// Update display
	updateStarDisplay(0);

	// Show the modal
	rateItModal.style.display = "flex";
	console.log("Rate-it modal opened successfully");
};
