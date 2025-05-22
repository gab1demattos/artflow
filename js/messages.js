/**
 * Messages page functionality
 * Handles sending and receiving messages in real-time
 */

// Global variables
let selectedUserId = null;
let messagePollingInterval = null;
const POLLING_INTERVAL = 3000; // 3 seconds

// DOM Elements
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

// Event Listeners
document.addEventListener("DOMContentLoaded", () => {
	// Handle conversation selection
	if (conversationList) {
		const chatItems = conversationList.querySelectorAll(".chat-app__chat-item");
		chatItems.forEach((item) => {
			item.addEventListener("click", () => selectConversation(item));
		});

		// If there's a stored conversation, select it
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

	// Handle message sending
	if (messageInput && sendButton) {
		// Send on button click
		sendButton.addEventListener("click", sendMessage);

		// Send on Enter key
		messageInput.addEventListener("keypress", (event) => {
			if (event.key === "Enter") {
				event.preventDefault();
				sendMessage();
			}
		});
	}

	// Handle conversation search
	if (conversationSearch) {
		conversationSearch.addEventListener("input", filterConversations);
	}

	// Handle dropdown menu toggle
	if (menuButton && dropdownMenu) {
		menuButton.addEventListener("click", toggleDropdownMenu);

		// Close dropdown when clicking outside
		document.addEventListener("click", (event) => {
			if (
				!menuButton.contains(event.target) &&
				!dropdownMenu.contains(event.target)
			) {
				dropdownMenu.classList.remove("active");
			}
		});
	}

	// Handle visit profile button click
	if (visitProfileButton) {
		visitProfileButton.addEventListener("click", visitUserProfile);
	}

	// Handle delete conversation button click
	if (deleteConversationButton) {
		deleteConversationButton.addEventListener(
			"click",
			confirmDeleteConversation
		);
	}

	// Check URL parameters for direct messaging
	// Only check URL parameters if not already initialized from PHP
	if (!window.conversationInitialized) {
		const urlParams = new URLSearchParams(window.location.search);
		const directMessageUserId = urlParams.get("user_id");
		if (directMessageUserId) {
			createOrSelectConversation(parseInt(directMessageUserId));
		}
	}
});

// Track if conversation has been initialized
window.conversationInitialized = false;

/**
 * Create or select a conversation with a specific user
 * @param {number} userId - User ID to chat with
 */
function createOrSelectConversation(userId) {
	// Prevent duplicate initialization
	if (window.conversationInitialized) {
		return;
	}
	window.conversationInitialized = true;

	// First check if conversation already exists in the list
	const existingItem = document.querySelector(
		`.chat-app__chat-item[data-user-id="${userId}"]`
	);

	if (existingItem) {
		// Conversation exists, select it
		selectConversation(existingItem);
	} else {
		// Conversation doesn't exist yet, need to create it
		// First, fetch user information
		fetch(`/actions/get-user-info.php?user_id=${userId}`)
			.then((response) => response.json())
			.then((data) => {
				if (data.success) {
					const user = data.user;

					// Create a new conversation item
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

					// Add to conversation list
					conversationList.prepend(newItem);

					// Add click event
					newItem.addEventListener("click", () => selectConversation(newItem));

					// Select the conversation
					selectConversation(newItem);

					// Remove no conversations message if it exists
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

/**
 * Select a conversation and load its messages
 * @param {HTMLElement} item - The selected conversation item
 */
function selectConversation(item) {
	// Remove active class from all conversations
	document
		.querySelectorAll(".chat-app__chat-item")
		.forEach((el) => el.classList.remove("active"));

	// Add active class to selected conversation
	item.classList.add("active");

	// Get user ID and name for this conversation
	selectedUserId = parseInt(item.dataset.userId);
	const username = item.querySelector(".chat-app__username").textContent;

	// Update header with current chat user
	currentChatUser.textContent = username;

	// Enable input and send button
	messageInput.disabled = false;
	sendButton.disabled = false;
	messageInput.focus();

	// Load messages for this conversation
	loadMessages();

	// Start polling for new messages
	if (messagePollingInterval) {
		clearInterval(messagePollingInterval);
	}
	messagePollingInterval = setInterval(loadMessages, POLLING_INTERVAL);

	// Store the selected conversation in session storage
	sessionStorage.setItem("selectedUserId", selectedUserId);
}

/**
 * Load messages for the selected conversation
 */
function loadMessages() {
	if (!selectedUserId) return;

	fetch(`/actions/get-messages.php?user_id=${selectedUserId}`)
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

/**
 * Display messages in the messages container
 * @param {Array} messages - Array of message objects
 */
function displayMessages(messages) {
	if (!messagesContainer) {
		console.error("Message container element not found!");
		return;
	}

	// Clear any empty state message
	messagesContainer.innerHTML = "";

	if (!messages || messages.length === 0) {
		messagesContainer.innerHTML = `
            <div class="chat-app__empty-state">
                <p>No messages yet. Start the conversation!</p>
            </div>
        `;
		return;
	}

	// Group messages by date
	let currentDate = "";

	messages.forEach((message) => {
		// Get message date
		const messageDate = new Date(message.timestamp);
		const formattedDate = messageDate.toLocaleDateString();

		// Add date separator if this is a new date
		if (formattedDate !== currentDate) {
			currentDate = formattedDate;

			const dateSeparator = document.createElement("div");
			dateSeparator.className = "chat-app__timestamp";
			dateSeparator.textContent = currentDate;
			messagesContainer.appendChild(dateSeparator);
		}

		// Create message element
		const messageEl = document.createElement("div");
		const isSentByCurrentUser =
			parseInt(message.sender_id) === parseInt(currentUser.id);

		// The classes need to be flipped here to match the CSS
		// Since "sent" messages should appear on the right and "received" on the left
		messageEl.className = isSentByCurrentUser
			? "chat-app__message chat-app__message--received" // Your message (should be on right)
			: "chat-app__message chat-app__message--sent"; // Their message (should be on left)

		messageEl.textContent = message.message;

		// Add message to container
		messagesContainer.appendChild(messageEl);
	});

	// Scroll to bottom
	messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

/**
 * Send a message to the selected user
 */
function sendMessage() {
	if (!selectedUserId || !messageInput.value.trim()) return;

	const messageText = messageInput.value.trim();

	// Clear input field
	messageInput.value = "";

	// Send message via API
	fetch("/actions/send-message.php", {
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
				// Load messages to show the new message
				loadMessages();

				// Update the preview text in the conversation list
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

/**
 * Filter conversations based on search input
 */
function filterConversations() {
	const searchTerm = conversationSearch.value.toLowerCase();
	const conversationItems = document.querySelectorAll(".chat-app__chat-item");

	conversationItems.forEach((item) => {
		const username = item
			.querySelector(".chat-app__username")
			.textContent.toLowerCase();

		if (username.includes(searchTerm)) {
			item.style.display = "";
		} else {
			item.style.display = "none";
		}
	});
}

/**
 * Toggle dropdown menu visibility
 */
function toggleDropdownMenu(event) {
	event.stopPropagation();
	dropdownMenu.classList.toggle("active");
}

/**
 * Visit the profile of the selected user
 */
function visitUserProfile() {
	if (!selectedUserId) return;

	// Fetch the username first and then redirect to the profile page with username
	fetch(`/actions/get-user-info.php?user_id=${selectedUserId}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success && data.user && data.user.username) {
				window.location.href = `/pages/profile.php?username=${encodeURIComponent(
					data.user.username
				)}`;
			}
		})
		.catch((error) => console.error("Error fetching user info:", error));
}

/**
 * Confirm conversation deletion with the irreversible modal
 */
function confirmDeleteConversation() {
	if (!selectedUserId) return;

	const username = currentChatUser.textContent;

	// Close the dropdown menu
	dropdownMenu.classList.remove("active");

	// Show the irreversible confirmation modal
	Modals.showIrreversibleModal(
		// Confirm callback - runs when user clicks "Yes"
		function () {
			deleteConversation();
		},
		// Cancel callback - runs when user clicks "Nevermind"
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
	fetch("/actions/delete-conversation.php", {
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
