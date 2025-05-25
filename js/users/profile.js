/*== PROFILE PAGE ==*/

// Security utility functions
/**
 * Escapes HTML to prevent XSS attacks
 * @param {string} unsafe - The unsafe string to be escaped
 * @return {string} The escaped string
 */
function escapeHtml(unsafe) {
	if (typeof unsafe !== "string") {
		return "";
	}
	return unsafe
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}

/**
 * Creates a safe DOM element with escaped content
 * @param {string} tag - HTML tag name
 * @param {Object} attributes - Element attributes
 * @param {string} textContent - Element text content
 * @return {HTMLElement} The created element
 */
function createSafeElement(tag, attributes = {}, textContent = "") {
	const element = document.createElement(tag);

	// Set attributes safely
	for (const [key, value] of Object.entries(attributes)) {
		if (key.startsWith("on")) continue; // Skip event handlers
		element.setAttribute(key, value);
	}

	// Set text content safely
	if (textContent) {
		element.textContent = textContent;
	}

	return element;
}

document.addEventListener("DOMContentLoaded", function () {
	const tabTriggers = document.querySelectorAll(".tab-trigger");
	const tabContents = document.querySelectorAll(".tab-content");

	tabTriggers.forEach((trigger) => {
		trigger.addEventListener("click", function () {
			// Remove active class from all triggers and contents
			tabTriggers.forEach((t) => t.classList.remove("active"));
			tabContents.forEach((c) => c.classList.remove("active"));

			// Add active class to clicked trigger
			this.classList.add("active");

			// Show corresponding tab content
			const tabId = this.getAttribute("data-tab");
			document.getElementById(tabId).classList.add("active");
		});
	});

	// Edit Profile button functionality
	const editProfileBtn = document.getElementById("edit-profile-button");
	const editProfileModalOverlay = document.getElementById(
		"edit-profile-modal-overlay"
	);
	const editProfileModal = document.getElementById("edit-profile-modal");
	const cancelEditProfileBtn = document.getElementById("cancel-edit-profile");

	if (editProfileBtn && editProfileModalOverlay) {
		editProfileBtn.addEventListener("click", function () {
			editProfileModalOverlay.classList.remove("hidden");
		});
	}

	if (cancelEditProfileBtn && editProfileModalOverlay) {
		cancelEditProfileBtn.addEventListener("click", function () {
			editProfileModalOverlay.classList.add("hidden");
		});
	}

	if (editProfileModalOverlay && editProfileModal) {
		// Close modal when clicking outside
		editProfileModalOverlay.addEventListener("click", function () {
			editProfileModalOverlay.classList.add("hidden");
		});

		// Prevent closing when clicking inside the modal
		editProfileModal.addEventListener("click", function (e) {
			e.stopPropagation();
		});
	}

	// Change Password button functionality
	const changePasswordBtn = document.getElementById("change-password-btn");
	const changePasswordModal = document.getElementById("change-password-modal");
	const changePasswordCancelBtn = document.querySelector(
		"#change-password-modal .cancel-modal"
	);
	const changePasswordCloseBtn = document.querySelector(
		"#change-password-modal .close"
	);

	if (changePasswordBtn && changePasswordModal) {
		console.log("Change Password button found:", changePasswordBtn);
		console.log("Change Password modal found:", changePasswordModal);

		changePasswordBtn.addEventListener("click", function (event) {
			console.log("Change Password button clicked");
			// Stop propagation to prevent the edit profile modal from closing
			event.stopPropagation();

			// Clear password fields every time the modal opens for security
			document.getElementById("old-password").value = "";
			document.getElementById("new-password").value = "";

			// Set both display flex and proper z-index
			changePasswordModal.style.display = "flex";
			console.log(
				"Changed modal display to:",
				changePasswordModal.style.display
			);
		});
	} else {
		console.log("Button or modal not found:", {
			button: changePasswordBtn,
			modal: changePasswordModal,
		});
	}

	if (changePasswordCancelBtn) {
		changePasswordCancelBtn.addEventListener("click", function () {
			changePasswordModal.style.display = "none";
		});
	}

	if (changePasswordCloseBtn) {
		changePasswordCloseBtn.addEventListener("click", function () {
			changePasswordModal.style.display = "none";
		});
	}

	// Close change password modal when clicking outside, but keep edit profile modal open
	window.addEventListener("click", function (event) {
		if (event.target == changePasswordModal) {
			changePasswordModal.style.display = "none";
		}
	});

	// Delete Account button functionality
	const deleteAccountBtn = document.getElementById("delete-account-btn");
	const irreversibleModal = document.getElementById("irreversible-modal");
	const irreversibleConfirmBtn = document.getElementById(
		"irreversible-confirm-btn"
	);
	const irreversibleCancelBtn = document.getElementById(
		"irreversible-cancel-btn"
	);

	if (deleteAccountBtn && irreversibleModal) {
		deleteAccountBtn.addEventListener("click", function (event) {
			// Stop propagation to prevent the edit profile modal from closing
			event.stopPropagation();

			// Update the modal message for account deletion
			const modalMessage = irreversibleModal.querySelector(
				".irreversible-modal-message"
			);
			if (modalMessage) {
				modalMessage.innerHTML = `
					<p>Are you sure you want to delete your account?</p>
					<p>This action cannot be undone and all your data will be permanently removed.</p>
				`;
			}

			// Set up the confirm button action for account deletion
			if (irreversibleConfirmBtn) {
				irreversibleConfirmBtn.onclick = function () {
					// Hide the modal
					irreversibleModal.classList.remove("show");

					// Redirect to the delete account action
					window.location.href =
						"../../actions/account_settings/delete-account-action.php";
				};
			}

			// Display the modal with show class - CSS handles display:flex
			irreversibleModal.classList.add("show");
		});
	}

	// Handle canceling the irreversible action
	if (irreversibleCancelBtn && irreversibleModal) {
		irreversibleCancelBtn.addEventListener("click", function () {
			irreversibleModal.classList.remove("show");
		});
	}

	// Close irreversible modal when clicking outside
	window.addEventListener("click", function (event) {
		if (event.target == irreversibleModal) {
			irreversibleModal.classList.remove("show");
		}
	});
});

/*== EDIT PROFILE ==*/

document.addEventListener("DOMContentLoaded", function () {
	// Profile image upload preview
	const profileImageInput = document.getElementById("profile_image");
	const profilePreview = document.getElementById("profile-preview");
	const deleteImageBtn = document.getElementById("delete-image-btn");
	const resetProfileImage = document.getElementById("reset_profile_image");
	const fileLabel = document.querySelector(".file-label");
	const cancelEditProfile = document.getElementById("cancel-edit-profile");
	const editProfileBtn = document.getElementById("edit-profile-button");
	const editProfileModalOverlay = document.getElementById(
		"edit-profile-modal-overlay"
	);

	// Initialize profile preview image when edit profile button is clicked
	if (editProfileBtn && profilePreview) {
		editProfileBtn.addEventListener("click", function () {
			// Get the current user profile image from the main profile image on the page
			// Updated selector to match the actual HTML structure
			const currentUserImg = document.querySelector(".profile-img img");

			if (
				currentUserImg &&
				currentUserImg.src &&
				!currentUserImg.src.includes("undefined")
			) {
				// Use the same image that's displayed on the profile page
				profilePreview.src = currentUserImg.src;
				console.log(
					"Setting preview to current user image:",
					currentUserImg.src
				);

				// Show or hide delete button based on whether it's the default image
				if (deleteImageBtn) {
					if (currentUserImg.src.includes("/default.png")) {
						deleteImageBtn.style.display = "none";
					} else {
						deleteImageBtn.style.display = "block";
					}
				}
			} else {
				// Fallback to default image if no profile image is found
				profilePreview.src = "../../images/user_pfp/default.png";
				console.log("Setting preview to default image (fallback)");

				// Hide delete button since we're showing the default image
				if (deleteImageBtn) {
					deleteImageBtn.style.display = "none";
				}
			}

			// Add error handler for any image loading issues
			profilePreview.onerror = function () {
				console.log("Failed to load profile image, using fallback");
				this.src = window.location.origin + "../../images/user_pfp/default.png";

				if (deleteImageBtn) {
					deleteImageBtn.style.display = "none";
				}
			};
		});
	}

	// Handle profile image change
	if (profileImageInput) {
		profileImageInput.addEventListener("change", function (e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function (e) {
					profilePreview.src = e.target.result;
				};
				reader.readAsDataURL(file);

				// Update label to show selected filename
				if (fileLabel) {
					const fileName =
						file.name.length > 15
							? file.name.substring(0, 12) + "..."
							: file.name;
					fileLabel.textContent = fileName;
				}
			}
		});
	}

	// Handle delete image button click
	if (deleteImageBtn) {
		deleteImageBtn.addEventListener("click", function () {
			profilePreview.src = "../../images/user_pfp/default.png";
			if (profileImageInput) {
				profileImageInput.value = "";
			}
			if (resetProfileImage) {
				resetProfileImage.value = "1"; // Set the hidden input to indicate reset
			}
			// Update label to show default text
			if (fileLabel) {
				fileLabel.textContent = "Choose file";
			}
		});
	}

	// Show/hide delete button based on preview image
	if (profilePreview) {
		profilePreview.addEventListener("load", function () {
			if (this.src !== "../../images/user_pfp/default.png") {
				deleteImageBtn.style.display = "block";
			} else {
				deleteImageBtn.style.display = "none";
			}
		});
	}

	// Make reviews list draggable when there are more than 2 reviews
	const reviewsList = document.querySelector(".reviews-list");
	if (reviewsList) {
		const reviewItems = reviewsList.querySelectorAll(".review-card");

		// Make draggable if there are any reviews
		if (reviewItems.length > 0) {
			reviewsList.classList.add("draggable");

			// Calculate exact height for 2 reviews
			if (reviewItems.length >= 2) {
				const firstReviewHeight = reviewItems[0].offsetHeight;
				const secondReviewHeight =
					reviewItems.length > 1 ? reviewItems[1].offsetHeight : 0;
				const gapHeight = 24; // 1.5rem = 24px typically

				// Set max-height to show exactly 2 reviews plus the gap between them
				reviewsList.style.maxHeight =
					firstReviewHeight + secondReviewHeight + gapHeight + "px";
			}

			let isDown = false;
			let startY;
			let scrollTop;

			// Mouse events for drag scrolling
			reviewsList.addEventListener("mousedown", (e) => {
				isDown = true;
				reviewsList.classList.add("active");
				startY = e.pageY - reviewsList.offsetTop;
				scrollTop = reviewsList.scrollTop;
			});

			reviewsList.addEventListener("mouseleave", () => {
				isDown = false;
				reviewsList.classList.remove("active");
			});

			reviewsList.addEventListener("mouseup", () => {
				isDown = false;
				reviewsList.classList.remove("active");
			});

			reviewsList.addEventListener("mousemove", (e) => {
				if (!isDown) return;
				e.preventDefault();
				const y = e.pageY - reviewsList.offsetTop;
				const walk = (y - startY) * 2; // Scroll speed multiplier
				reviewsList.scrollTop = scrollTop - walk;
			});
		}
	}
});
