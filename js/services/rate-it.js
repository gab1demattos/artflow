let rateItModal = null;
let rateItForm = null;
let ratingValue = null;
let ratingText = null;
let stars = null;
let currentRating = 0;

document.addEventListener("DOMContentLoaded", function () {
	rateItModal = document.getElementById("rate-it-modal-overlay");
	
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
	
	stars.forEach((star) => {
		star.addEventListener("mouseover", function () {
			const hoverRating = parseFloat(this.getAttribute("data-value"));
			updateStarDisplay(hoverRating);
		});
		
		star.addEventListener("click", function () {
			currentRating = parseFloat(this.getAttribute("data-value"));
			updateStarDisplay(currentRating);
		});
	});
	
	if (starsContainer) {
		starsContainer.addEventListener("mouseleave", function () {
			updateStarDisplay(currentRating);
		});
	}
	
	if (closeButton) {
		closeButton.addEventListener("click", function () {
			closeRateItModal();
		});
	}
	
	rateItModal.addEventListener("click", function (event) {
		if (event.target === rateItModal) {
			closeRateItModal();
		}
	});
	
	if (rateItForm) {
		rateItForm.addEventListener("submit", function (event) {
			if (currentRating === 0) {
				event.preventDefault();
				alert("Please select a rating before submitting");
				return false;
				}
		});
	}
});

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
		const starIndex = Math.ceil(starValue) - 1;
		const starPosition = stars.length > 5 ? starIndex * 2 : starIndex;
		const currentStar = stars[starPosition];
		
		if (!currentStar) return;
		
		if (rating >= starValue) {
			currentStar.textContent = "★";
			currentStar.classList.add("active");
			currentStar.classList.remove("half-filled", "half");
		}
		else if (rating === starValue - 0.5) {
			currentStar.textContent = "&#9733;";
			currentStar.classList.add("active", "half-filled");
			currentStar.classList.remove("half");
		}
		else {
			currentStar.textContent = "★";
			currentStar.classList.remove("active", "half-filled", "half");
		}
	});
	
	if (ratingText) ratingText.textContent = rating.toFixed(1);
	if (ratingValue) ratingValue.value = rating;
}

function closeRateItModal() {
	if (rateItModal) {
		rateItModal.style.display = "none";
	}
}

window.openRateItModal = function (serviceId, serviceTitle) {
	console.log("Opening rate-it modal for:", serviceId, serviceTitle);
	
	if (!rateItModal) {
		rateItModal = document.getElementById("rate-it-modal-overlay");
	}
	if (!rateItForm) {
		rateItForm = document.getElementById("rate-it-form");
	}
	
	if (!rateItModal || !rateItForm) {
		console.error(
			"Rate-it modal elements not found. Modal may not be included in the page."
		);
		return;
	}
	
	rateItForm.reset();
	currentRating = 0;
	
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
	
	updateStarDisplay(0);
	
	rateItModal.style.display = "flex";
	console.log("Rate-it modal opened successfully");
};
