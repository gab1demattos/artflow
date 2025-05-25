document.addEventListener("DOMContentLoaded", function () {
	function getCookie(name) {
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${name}=`);
		if (parts.length === 2) return parts.pop().split(";").shift();
		return null;
	}
	
	function deleteCookie(name) {
		document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
	}
	
	const showGoFlow = getCookie("show_go_flow");
	console.log("Cookie show_go_flow:", showGoFlow);
	
	console.log(
		"sessionStorage signup_success:",
		sessionStorage.getItem("signup_success")
	);
	
	if (showGoFlow === "true") {
		console.log("Found cookie, showing go flow modal");
		if (window.showGoFlowModal) {
			window.showGoFlowModal();
		} else if (
			window.Modals &&
			window.Modals.overlays &&
			window.Modals.overlays.goFlow
		) {
			window.Modals.show("goFlow");
		} else {
			const goFlow = document.getElementById("goflow-modal-overlay");
			if (goFlow) {
				goFlow.classList.remove("hidden");
			}
		}
		
		deleteCookie("show_go_flow");
	}
	
	if (sessionStorage.getItem("signup_success") === "true") {
		console.log("Found sessionStorage value, showing go flow modal");
		if (window.showGoFlowModal) {
			window.showGoFlowModal();
		} else if (
			window.Modals &&
			window.Modals.overlays &&
			window.Modals.overlays.goFlow
		) {
			window.Modals.show("goFlow");
		} else {
			const goFlow = document.getElementById("goflow-modal-overlay");
			if (goFlow) {
				goFlow.classList.remove("hidden");
			}
		}
		
		sessionStorage.removeItem("signup_success");
	}
});
