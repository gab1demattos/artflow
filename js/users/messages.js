
let selectedUserId = null;
let messagePollingInterval = null;
const POLLING_INTERVAL = 3000; // 3 seconds

const conversationList = document.getElementById("conversation-list");
const conversationSearch = document.getElementById("conversation-search");
const messagesContainer = document.getElementById("messages-container");
const messageInput = document.getElementById("message-input");
const sendButton = document.getElementById("send-button");
const currentChatUser = document.getElementById("current-chat-user");
const menuButton = document.querySelector(".chat-app__menu-button");
const dropdownMenu = document.querySelector(".chat-app__dropdown-menu");
const visitProfileButton = document.getElementById("visit-profile");
const deleteConversationButton = document.getElementById("delete-conversation");

document.addEventListener("DOMContentLoaded", () => {
	if (conversationList) {
		const chatItems = conversationList.querySelectorAll(".chat-app__chat-item");
		chatItems.forEach((item) => {
			item.addEventListener("click", () => selectConversation(item));
		});

		const storedUserId = sessionStorage.getItem("selectedUserId");
		if (storedUserId) {
			const conversationItem = document.querySelector(
				`.chat-app__chat-item[data-user-id="${storedUserId}"]`
			);
			if (conversationItem) {
				selectConversation(conversationItem);
			}
		}
	}

	if (messageInput && sendButton) {
		sendButton.addEventListener("click", sendMessage);

		messageInput.addEventListener("keypress", (event) => {
			if (event.key === "Enter") {
				event.preventDefault();
				sendMessage();
			}
		});
	}

	if (conversationSearch) {
		conversationSearch.addEventListener("input", filterConversations);
	}

	checkIfMobile();

	if (menuButton && dropdownMenu) {
		menuButton.addEventListener("click", toggleDropdownMenu);

		document.addEventListener("click", (event) => {
			if (
				!menuButton.contains(event.target) &&
				!dropdownMenu.contains(event.target)
			) {
				dropdownMenu.classList.remove("active");
			}
		});
	}

	if (visitProfileButton) {
		visitProfileButton.addEventListener("click", visitUserProfile);
	}

	if (deleteConversationButton) {
		deleteConversationButton.addEventListener(
			"click",
			confirmDeleteConversation
		);
	}

	if (!window.conversationInitialized) {
		const urlParams = new URLSearchParams(window.location.search);
		const directMessageUserId = urlParams.get("user_id");
		if (directMessageUserId) {
			createOrSelectConversation(parseInt(directMessageUserId));
		}
	}
});

window.conversationInitialized = false;


function createOrSelectConversation(userId) {
	if (window.conversationInitialized) {
		return;
	}
	window.conversationInitialized = true;

	const existingItem = document.querySelector(
		`.chat-app__chat-item[data-user-id="${userId}"]`
	);

	if (existingItem) {
		selectConversation(existingItem);
	} else {
		fetch(`/actions/messages/get-user-info.php?user_id=${userId}`)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					const user = data.user;

					const newItem = document.createElement("div");
					newItem.className = "chat-app__chat-item";
					newItem.dataset.userId = userId;

					const profileImage =
						user.profile_image || "/images/user_pfp/default.png";

					newItem.innerHTML = `
                        <img src="${profileImage}" alt="profile" class="chat-app__avatar" />
                        <div class="chat-app__chat-text">
                            <div class="chat-app__username">${user.username}</div>
                            <div class="chat-app__message-preview">Start a conversation</div>
                        </div>
                    `;

					conversationList.prepend(newItem);

					newItem.addEventListener("click", () => selectConversation(newItem));

					selectConversation(newItem);

					const noConversationsMsg = document.querySelector(
						".chat-app__no-conversations"
					);
					if (noConversationsMsg) {
						noConversationsMsg.remove();
					}
				}
			})
			.catch((error) => console.error("Error fetching user info:", error));
	}
}

function selectConversation(item) {
	document
		.querySelectorAll(".chat-app__chat-item")
		.forEach((el) => el.classList.remove("active"));

	item.classList.add("active");

	selectedUserId = parseInt(item.dataset.userId);
	const username = item.querySelector(".chat-app__username").textContent;

	currentChatUser.textContent = username;

	messageInput.disabled = false;
	sendButton.disabled = false;
	messageInput.focus();

	loadMessages();

	if (messagePollingInterval) {
		clearInterval(messagePollingInterval);
	}
	messagePollingInterval = setInterval(loadMessages, POLLING_INTERVAL);

	sessionStorage.setItem("selectedUserId", selectedUserId);
}

function loadMessages() {
	if (!selectedUserId) return;

	fetch(`/actions/messages/get-messages.php?user_id=${selectedUserId}`)
		.then((response) => {
			if (!response.ok) {
				console.error("Network response was not ok: " + response.status);
				throw new Error("Network response was not ok: " + response.status);
			}
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				displayMessages(data.messages);
			} else {
				console.error("Error in response: " + (data.error || "Unknown error"));
			}
		})
		.catch((error) => {
			console.error("Error loading messages:", error.message);
		});
}


function displayMessages(messages) {
	if (!messagesContainer) {
		console.error("Message container element not found!");
		return;
	}

	messagesContainer.innerHTML = "";

	if (!messages || messages.length === 0) {
		messagesContainer.innerHTML = `
            <div class="chat-app__empty-state">
                <p>No messages yet. Start the conversation!</p>
            </div>
        `;
		return;
	}

	let currentDate = "";

	messages.forEach((message) => {
		const messageDate = new Date(message.timestamp);
		const formattedDate = messageDate.toLocaleDateString();

		if (formattedDate !== currentDate) {
			currentDate = formattedDate;

			const dateSeparator = document.createElement("div");
			dateSeparator.className = "chat-app__timestamp";
			dateSeparator.textContent = currentDate;
			messagesContainer.appendChild(dateSeparator);
		}

		const messageEl = document.createElement("div");
		const isSentByCurrentUser =
			parseInt(message.sender_id) === parseInt(currentUser.id);

		messageEl.className = isSentByCurrentUser
			? "chat-app__message chat-app__message--received"
			: "chat-app__message chat-app__message--sent"; 

		messageEl.textContent = message.message;

		messagesContainer.appendChild(messageEl);
	});

	messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function sendMessage() {
	if (!selectedUserId || !messageInput.value.trim()) return;

	const messageText = messageInput.value.trim();

	messageInput.value = "";

	fetch("/actions/messages/send-message.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			receiver_id: selectedUserId,
			message: messageText,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				loadMessages();

				const conversationItem = document.querySelector(
					`.chat-app__chat-item[data-user-id="${selectedUserId}"]`
				);
				if (conversationItem) {
					const previewElement = conversationItem.querySelector(
						".chat-app__message-preview"
					);
					if (previewElement) {
						const previewText =
							messageText.length > 25
								? messageText.substring(0, 25) + "..."
								: messageText;
						previewElement.textContent = previewText;
					}
				}
			}
		})
		.catch((error) => console.error("Error sending message:", error));
}


function filterConversations() {
	const searchTerm = conversationSearch.value.toLowerCase().trim();
	const conversationItems = document.querySelectorAll(".chat-app__chat-item");
	const noConversationsMsg = document.querySelector(
		".chat-app__no-conversations"
	);

	if (conversationItems.length === 0 && noConversationsMsg) {
		return;
	}

	let anyVisible = false;

	conversationItems.forEach((item) => {
		const username = item
			.querySelector(".chat-app__username")
			.textContent.toLowerCase();

		if (username.includes(searchTerm)) {
			item.style.display = "";
			anyVisible = true;
		} else {
			item.style.display = "none";
		}
	});

	const noResultsMsg = document.getElementById("no-search-results");

	if (!anyVisible && searchTerm !== "") {
		if (!noResultsMsg) {
			const message = document.createElement("p");
			message.id = "no-search-results";
			message.className = "chat-app__no-conversations";
			message.textContent = "No conversations match your search";
			conversationList.appendChild(message);
		}
	} else if (noResultsMsg) {
		noResultsMsg.remove();
	}
}

function toggleDropdownMenu(event) {
	event.stopPropagation();
	dropdownMenu.classList.toggle("active");
}

function visitUserProfile() {
	if (!selectedUserId) return;

	fetch(`/actions/messages/get-user-info.php?user_id=${selectedUserId}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success && data.user && data.user.username) {
				window.location.href = `/pages/users/profile.php?username=${encodeURIComponent(
					data.user.username
				)}`;
			}
		})
		.catch((error) => console.error("Error fetching user info:", error));
}


function confirmDeleteConversation() {
	if (!selectedUserId) return;

	const username = currentChatUser.textContent;

	dropdownMenu.classList.remove("active");

	Modals.showIrreversibleModal(
		function () {
			deleteConversation();
		},
		function () {
			// Do nothing, just close the modal
		}
	);
}

/**
 * Delete the selected conversation
 */
function deleteConversation() {
	if (!selectedUserId) return;

	// Send delete request to the server
	fetch("/actions/messages/delete-conversation.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			other_user_id: selectedUserId,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				// Remove conversation from the list
				const conversationItem = document.querySelector(
					`.chat-app__chat-item[data-user-id="${selectedUserId}"]`
				);

				if (conversationItem) {
					conversationItem.remove();
				}

				// Clear the messages container
				messagesContainer.innerHTML = `
				<div class="chat-app__empty-state">
					<p>Select a conversation to start chatting</p>
				</div>
			`;

				// Reset the selected user
				selectedUserId = null;
				currentChatUser.textContent = "";

				// Disable the input and send button
				messageInput.disabled = true;
				sendButton.disabled = true;

				// Stop polling
				if (messagePollingInterval) {
					clearInterval(messagePollingInterval);
					messagePollingInterval = null;
				}

				// Clear session storage
				sessionStorage.removeItem("selectedUserId");

				// Check if there are any conversations left
				const remainingConversations = document.querySelectorAll(
					".chat-app__chat-item"
				);
				if (remainingConversations.length === 0) {
					// Add the no conversations message
					conversationList.innerHTML = `
					<p class="chat-app__no-conversations">No conversations yet. Start chatting with someone!</p>
				`;
				}
			} else {
				console.error("Error deleting conversation:", data.error);
				alert("Failed to delete conversation. Please try again.");
			}
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("An error occurred. Please try again.");
		});
}

/**
 * Checks if the device is a mobile device and applies mobile-specific behavior
 */
function checkIfMobile() {
	// Check if screen width is mobile size (under 768px)
	const isMobile = window.innerWidth < 768;

	// Set up mobile specific UI and interactions
	setupMobileLayout(isMobile);

	// Add window resize listener to check for orientation changes
	window.addEventListener("resize", handleResize);
}

/**
 * Sets up mobile specific layout and interactions
 * @param {boolean} isMobile - Whether the device is mobile
 */
function setupMobileLayout(isMobile) {
	const sidebar = document.querySelector(".chat-app__sidebar");
	const mainChat = document.querySelector(".chat-app__main");
	const backButton = document.getElementById("back-to-conversations");

	if (!sidebar || !mainChat || !backButton) return;

	if (isMobile) {
		// Initialize with sidebar visible and main chat hidden
		if (!selectedUserId) {
			sidebar.classList.remove("hidden");
			mainChat.classList.remove("visible");
		} else {
			// If a conversation is already selected, show the chat view
			sidebar.classList.add("hidden");
			mainChat.classList.add("visible");
			backButton.classList.add("visible");
		}

		// Set up back button functionality
		backButton.addEventListener("click", showConversationList);

		// Override conversation selection behavior for mobile
		const chatItems = document.querySelectorAll(".chat-app__chat-item");
		chatItems.forEach((item) => {
			// Remove any existing event listeners first
			const newItem = item.cloneNode(true);
			item.parentNode.replaceChild(newItem, item);

			// Add our mobile-specific click event
			newItem.addEventListener("click", function () {
				const userId = this.dataset.userId;
				const existingItem = document.querySelector(
					`.chat-app__chat-item[data-user-id="${userId}"]`
				);

				if (existingItem) {
					selectConversation(existingItem);
					showMainChatView();
				}
			});
		});
	} else {
		// For desktop/tablet, use normal layout
		sidebar.classList.remove("hidden");
		mainChat.classList.remove("visible");
		backButton.classList.remove("visible");

		// Remove mobile-specific event listeners
		backButton.removeEventListener("click", showConversationList);
	}
}

/**
 * Shows the conversation list/sidebar view on mobile
 * No animations, just toggle visibility classes
 */
function showConversationList() {
	const sidebar = document.querySelector(".chat-app__sidebar");
	const mainChat = document.querySelector(".chat-app__main");
	const backButton = document.getElementById("back-to-conversations");

	if (!sidebar || !mainChat) return;

	// No animations, just toggle classes
	sidebar.classList.remove("hidden");
	mainChat.classList.remove("visible");
	backButton.classList.remove("visible");
}

/**
 * Shows the main chat view on mobile
 * No animations, just toggle visibility classes
 */
function showMainChatView() {
	const sidebar = document.querySelector(".chat-app__sidebar");
	const mainChat = document.querySelector(".chat-app__main");
	const backButton = document.getElementById("back-to-conversations");

	if (!sidebar || !mainChat) return;

	// No animations, just toggle classes
	sidebar.classList.add("hidden");
	mainChat.classList.add("visible");
	backButton.classList.add("visible");
}

/**
 * Handles window resize events for responsive behavior
 */
function handleResize() {
	// Check if device is mobile
	const isMobile = window.innerWidth < 768;

	// Set up layout based on screen size
	setupMobileLayout(isMobile);

	// If user has selected a conversation, make sure it stays visible after resize
	if (selectedUserId) {
		// Re-scroll to bottom of messages if needed
		if (messagesContainer) {
			messagesContainer.scrollTop = messagesContainer.scrollHeight;
		}
	}
}
