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

function createSafeElement(tag, attributes = {}, textContent = "") {
	const element = document.createElement(tag);
	for (const [key, value] of Object.entries(attributes)) {
		if (key.startsWith("on")) continue;
		element.setAttribute(key, value);
	}
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
			tabTriggers.forEach((t) => t.classList.remove("active"));
			tabContents.forEach((c) => c.classList.remove("active"));
			this.classList.add("active");
			const tabId = this.getAttribute("data-tab");
			document.getElementById(tabId).classList.add("active");
		});
	});

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
		editProfileModalOverlay.addEventListener("click", function () {
			editProfileModalOverlay.classList.add("hidden");
		});
		editProfileModal.addEventListener("click", function (e) {
			e.stopPropagation();
		});
	}
	
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
			event.stopPropagation();
			document.getElementById("old-password").value = "";
			document.getElementById("new-password").value = "";
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
	
	window.addEventListener("click", function (event) {
		if (event.target == changePasswordModal) {
			changePasswordModal.style.display = "none";
		}
	});
	
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
			event.stopPropagation();
			const modalMessage = irreversibleModal.querySelector(
				".irreversible-modal-message"
			);
			if (modalMessage) {
				modalMessage.innerHTML = `
					<p>Are you sure you want to delete your account?</p>
					<p>This action cannot be undone and all your data will be permanently removed.</p>
				`;
			}
			if (irreversibleConfirmBtn) {
				irreversibleConfirmBtn.onclick = function () {
					irreversibleModal.classList.remove("show");
					window.location.href =
						"../../actions/account_settings/delete-account-action.php";
				};
			}
			irreversibleModal.classList.add("show");
		});
	}
	
	if (irreversibleCancelBtn && irreversibleModal) {
		irreversibleCancelBtn.addEventListener("click", function () {
			irreversibleModal.classList.remove("show");
		});
	}
	
	window.addEventListener("click", function (event) {
		if (event.target == irreversibleModal) {
			irreversibleModal.classList.remove("show");
		}
	});
});

document.addEventListener("DOMContentLoaded", function () {
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
	
	if (editProfileBtn && profilePreview) {
		editProfileBtn.addEventListener("click", function () {
			const currentUserImg = document.querySelector(".profile-img img");
			if (
				currentUserImg &&
				currentUserImg.src &&
				!currentUserImg.src.includes("undefined")
			) {
				profilePreview.src = currentUserImg.src;
				console.log(
					"Setting preview to current user image:",
					currentUserImg.src
				);
				if (deleteImageBtn) {
					if (currentUserImg.src.includes("/default.png")) {
						deleteImageBtn.style.display = "none";
					} else {
						deleteImageBtn.style.display = "block";
					}
				}
			} else {
				profilePreview.src = "../../images/user_pfp/default.png";
				console.log("Setting preview to default image (fallback)");
				if (deleteImageBtn) {
					deleteImageBtn.style.display = "none";
				}
			}
			profilePreview.onerror = function () {
				console.log("Failed to load profile image, using fallback");
				this.src = window.location.origin + "../../images/user_pfp/default.png";
				if (deleteImageBtn) {
					deleteImageBtn.style.display = "none";
				}
			};
		});
	}
	
	if (profileImageInput) {
		profileImageInput.addEventListener("change", function (e) {
			const file = e.target.files[0];
			if (file) {
				const reader = new FileReader();
				reader.onload = function (e) {
					profilePreview.src = e.target.result;
				};
				reader.readAsDataURL(file);
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
	
	if (deleteImageBtn) {
		deleteImageBtn.addEventListener("click", function () {
			profilePreview.src = "../../images/user_pfp/default.png";
			if (profileImageInput) {
				profileImageInput.value = "";
			}
			if (resetProfileImage) {
				resetProfileImage.value = "1";
			}
			if (fileLabel) {
				fileLabel.textContent = "Choose file";
			}
		});
	}
	
	if (profilePreview) {
		profilePreview.addEventListener("load", function () {
			if (this.src !== "../../images/user_pfp/default.png") {
				deleteImageBtn.style.display = "block";
			} else {
				deleteImageBtn.style.display = "none";
			}
		});
	}
	
	const reviewsList = document.querySelector(".reviews-list");
	if (reviewsList) {
		const reviewItems = reviewsList.querySelectorAll(".review-card");
		if (reviewItems.length > 0) {
			reviewsList.classList.add("draggable");
			if (reviewItems.length >= 2) {
				const firstReviewHeight = reviewItems[0].offsetHeight;
				const secondReviewHeight =
					reviewItems.length > 1 ? reviewItems[1].offsetHeight : 0;
				const gapHeight = 24;
				reviewsList.style.maxHeight =
					firstReviewHeight + secondReviewHeight + gapHeight + "px";
			}
			let isDown = false;
			let startY;
			let scrollTop;
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
				const walk = (y - startY) * 2;
				reviewsList.scrollTop = scrollTop - walk;
			});
		}
	}
});
