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

// Event Listeners
document.addEventListener("DOMContentLoaded", () => {
	// Handle conversation selection
	if (conversationList) {
		const chatItems = conversationList.querySelectorAll(".chat-app__chat-item");
		chatItems.forEach((item) => {
			item.addEventListener("click", () => selectConversation(item));
		});
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

	// Check URL parameters for direct messaging
	const urlParams = new URLSearchParams(window.location.search);
	const directMessageUserId = urlParams.get("user_id");
	if (directMessageUserId) {
		createOrSelectConversation(parseInt(directMessageUserId));
	}
});

/**
 * Create or select a conversation with a specific user
 * @param {number} userId - User ID to chat with
 */
function createOrSelectConversation(userId) {
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
                            <strong>${user.username}</strong>
                            <span>Start a conversation</span>
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
	const username = item.querySelector("strong").textContent;

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
}

/**
 * Load messages for the selected conversation
 */
function loadMessages() {
	if (!selectedUserId) return;

	fetch(`/actions/get-messages.php?user_id=${selectedUserId}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				displayMessages(data.messages);
			}
		})
		.catch((error) => console.error("Error loading messages:", error));
}

/**
 * Display messages in the messages container
 * @param {Array} messages - Array of message objects
 */
function displayMessages(messages) {
	if (!messagesContainer) return;

	// Clear any empty state message
	messagesContainer.innerHTML = "";

	if (messages.length === 0) {
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
		const isSentByCurrentUser = message.sender_id === currentUser.id;

		messageEl.className = isSentByCurrentUser
			? "chat-app__message chat-app__message--received"
			: "chat-app__message chat-app__message--sent";

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
					const previewSpan = conversationItem.querySelector(
						".chat-app__chat-text span"
					);
					if (previewSpan) {
						const previewText =
							messageText.length > 25
								? messageText.substring(0, 25) + "..."
								: messageText;
						previewSpan.textContent = previewText;
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
		const username = item.querySelector("strong").textContent.toLowerCase();

		if (username.includes(searchTerm)) {
			item.style.display = "";
		} else {
			item.style.display = "none";
		}
	});
}
