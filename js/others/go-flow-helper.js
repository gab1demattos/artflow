

document.addEventListener("DOMContentLoaded", function () {
	function getQueryParam(param) {
		const urlParams = new URLSearchParams(window.location.search);
		return urlParams.get(param);
	}

	const showGoFlow = getQueryParam("showGoFlow") === "true";

	if (showGoFlow) {
		console.log("showGoFlow parameter detected in URL, showing modal");

		const goFlowModal = document.getElementById("goflow-modal-overlay");

		if (goFlowModal) {
			console.log("Found go-with-flow modal element");

			document.querySelectorAll(".modal-overlay").forEach(function (modal) {
				if (modal !== goFlowModal) {
					modal.classList.add("hidden");
				}
			});

			setTimeout(function () {
				goFlowModal.classList.remove("hidden");
				console.log("Go with flow modal shown via go-flow-helper.js");

				const url = new URL(window.location.href);
				url.searchParams.delete("showGoFlow");
				history.replaceState({}, document.title, url);
			}, 300);

		} else {
			console.error("Go with flow modal element not found");
		}
	}
});
