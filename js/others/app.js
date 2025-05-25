document.addEventListener("DOMContentLoaded", function () {
	console.log("All sessionStorage items:");
	for (let i = 0; i < sessionStorage.length; i++) {
		const key = sessionStorage.key(i);
		console.log(`${key}: ${sessionStorage.getItem(key)}`);
	}
	console.log(
		"signup_success value:",
		sessionStorage.getItem("signup_success")
	);
	
	if (window.Modals) Modals.init();
	if (window.Categories) Categories.init();
	
	if (
		sessionStorage.getItem("signup_success") === "true" &&
		window.showGoFlowModal
	) {
		console.log("Showing go with flow modal from app.js");
		window.showGoFlowModal();
		sessionStorage.removeItem("signup_success");
	} else {
		console.log("Not showing go with flow modal:", {
			signupSuccess: sessionStorage.getItem("signup_success"),
			showGoFlowModalExists: !!window.showGoFlowModal,
		});
	}
	console.log("Application initialized");
});
