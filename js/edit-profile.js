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
