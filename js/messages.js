/**
 * Messages page functionality
 * Handles sending and receiving messages in real-time
 */

// Security utility functions
/**
 * Escapes HTML to prevent XSS attacks
 * @param {string} unsafe - The unsafe string to be escaped
 * @return {string} The escaped string
 */
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

/**
 * Creates a safe DOM element with escaped content
 * @param {string} tag - HTML tag name
 * @param {Object} attributes - Element attributes
 * @param {string} textContent - Element text content
 * @return {HTMLElement} The created element
 */
function createSafeElement(tag, attributes = {}, textContent = "") {
	const element = document.createElement(tag);

	// Set attributes safely
	for (const [key, value] of Object.entries(attributes)) {
		if (key.startsWith("on")) continue; // Skip event handlers
		element.setAttribute(key, value);
	}

	// Set text content safely
	if (textContent) {
		element.textContent = textContent;
	}

	return element;
}

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

	// Handle responsive design for mobile
	checkIfMobile();

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
		fetch(`/actions/messages/get-user-info.php?user_id=${userId}`)
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

					// Add click event - different for mobile vs desktop
					if (window.innerWidth < 768) {
						newItem.addEventListener("click", function () {
							selectConversation(newItem);
							showMainChatView();
						});
					} else {
						newItem.addEventListener("click", () =>
							selectConversation(newItem)
						);
					}

					// Select the conversation
					selectConversation(newItem);

					// On mobile, switch to main chat view
					if (window.innerWidth < 768) {
						showMainChatView();
					}

					// Remove no Converstations message if it exists
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

	// Load messages for this conversation
	loadMessages();

	// Start polling for new messages
	if (messagePollingInterval) {
		clearInterval(messagePollingInterval);
	}
	messagePollingInterval = setInterval(loadMessages, POLLING_INTERVAL);

	// Store the selected conversation in session storage
	sessionStorage.setItem("selectedUserId", selectedUserId);

	// On mobile, focus the input after a slight delay to ensure UI is updated
	if (window.innerWidth < 768) {
		setTimeout(() => {
			messageInput.focus();
		}, 300);
	} else {
		messageInput.focus();
	}
}

/**
 * Load messages for the selected conversation
 */
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
		const emptyState = createSafeElement("div", {
			class: "chat-app__empty-state",
		});
		const emptyText = createSafeElement(
			"p",
			{},
			"No messages yet. Start the conversation!"
		);
		emptyState.appendChild(emptyText);
		messagesContainer.appendChild(emptyState);
		return;
	}

	// Group messages by date
	let currentDate = "";

	messages.forEach((message) => {
		// Get message date
		const messageDate = new Date(message.timestamp).toLocaleDateString();

		// Add date separator if date changes
		if (messageDate !== currentDate) {
			currentDate = messageDate;

			const dateSeparator = createSafeElement(
				"div",
				{ class: "chat-app__timestamp" },
				messageDate
			);
			messagesContainer.appendChild(dateSeparator);
		}

		// Create message element
		const messageElement = createSafeElement("div", {
			class: `chat-app__message ${
				message.sender_id == currentUser.id
					? "chat-app__message--received"
					: "chat-app__message--sent"
			}`,
			"data-message-id": message.id,
		});

		// Create and append the message text (properly escaped)
		const messageText = createSafeElement("div", {}, message.content);
		messageElement.appendChild(messageText);

		// Add the message to the container
		messagesContainer.appendChild(messageElement);
	});

	// Scroll to the bottom
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

	// Send the message via AJAX
	fetch("/actions/messages/send-message.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: `recipient_id=${encodeURIComponent(
			selectedUserId
		)}&content=${encodeURIComponent(messageText)}`,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				// Message sent successfully - add it to the chat
				const newMessage = {
					id: data.message_id,
					sender_id: currentUser.id,
					recipient_id: selectedUserId,
					content: messageText,
					timestamp: new Date().toISOString(),
				};

				// Create a new array with the new message
				const messages =
					messagesContainer.querySelectorAll(".chat-app__message");
				const hasMessages = messages.length > 0;

				// Display the message
				if (!hasMessages) {
					// If this is the first message, clear any empty state message
					displayMessages([newMessage]);
				} else {
					// Check if we need to add a date separator
					const today = new Date().toLocaleDateString();
					const lastDateSeparator = messagesContainer.querySelector(
						".chat-app__timestamp:last-child"
					);

					// If there's no date separator for today, add one
					if (!lastDateSeparator || lastDateSeparator.textContent !== today) {
						const dateSeparator = createSafeElement(
							"div",
							{ class: "chat-app__timestamp" },
							today
						);
						messagesContainer.appendChild(dateSeparator);
					}

					// Create and add the new message element
					const messageElement = createSafeElement("div", {
						class: "chat-app__message chat-app__message--received",
						"data-message-id": newMessage.id,
					});

					const messageTextElement = createSafeElement(
						"div",
						{},
						newMessage.content
					);
					messageElement.appendChild(messageTextElement);
					messagesContainer.appendChild(messageElement);

					// Scroll to the bottom
					messagesContainer.scrollTop = messagesContainer.scrollHeight;
				}

				// Update preview in conversation list
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
 * Searches for matching usernames in the conversation list
 */
function filterConversations() {
	const searchTerm = conversationSearch.value.toLowerCase().trim();
	const conversationItems = document.querySelectorAll(".chat-app__chat-item");
	const noConversationsMsg = document.querySelector(
		".chat-app__no-conversations"
	);

	// If there are no conversations, don't try to filter
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

	// Show a message if no results match the search
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
	fetch(`/actions/messages/get-user-info.php?user_id=${selectedUserId}`)
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

				// On mobile, return to conversation list view
				if (window.innerWidth < 768) {
					showConversationList();
				}

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
 * Animates the transition back to the conversation list
 */
function showConversationList() {
	const sidebar = document.querySelector(".chat-app__sidebar");
	const mainChat = document.querySelector(".chat-app__main");
	const backButton = document.getElementById("back-to-conversations");

	if (!sidebar || !mainChat) return;

	// Simply use the CSS classes for the transition
	// This prevents the double animation effect
	sidebar.classList.remove("hidden");
	mainChat.classList.remove("visible");
	backButton.classList.remove("visible");
}

/**
 * Shows the main chat view on mobile
 * Animates the transition to show the chat view
 */
function showMainChatView() {
	const sidebar = document.querySelector(".chat-app__sidebar");
	const mainChat = document.querySelector(".chat-app__main");
	const backButton = document.getElementById("back-to-conversations");

	if (!sidebar || !mainChat) return;

	// Simply use the CSS classes for the transition
	// This prevents the double animation effect
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
