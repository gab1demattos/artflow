/*== PROFILE PAGE ==*/

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
			profilePreview.src = "/images/user_pfp/default.png";
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
			if (this.src !== "/images/user_pfp/default.png") {
				deleteImageBtn.style.display = "block";
			} else {
				deleteImageBtn.style.display = "none";
			}
		});
	}
});
