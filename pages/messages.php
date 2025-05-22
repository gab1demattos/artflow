<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');
require_once(__DIR__ . '/../database/classes/message.class.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

// Redirect to home if not logged in
if (!$user) {
    header('Location: /');
    exit();
}

// Get all conversations for the current user
$conversations = Message::getConversationsForUser($user['id']);

drawHeader($user);
?>
<link rel="stylesheet" href="/css/messages.css">
<div id='messages-page'>
    <div class="chat-app__container">
        <aside class="chat-app__sidebar">
            <h1 class="chat-app__title">chats</h1>
            <div class="chat-app__search-bar">
                <input class="chat-app__search-input" type="text" placeholder="Search here..." id="conversation-search" />
                <img class="chat-app__search-icon" src="/images/logos/search.png" alt="search icon" />
            </div>
            <div class="chat-app__chat-list" id="conversation-list">
                <?php if (empty($conversations)): ?>
                    <p class="chat-app__no-conversations">No conversations yet. Start chatting with someone!</p>
                <?php else: ?>
                    <?php foreach ($conversations as $conversation):
                        $otherUserId = ($conversation['sender_id'] == $user['id']) ? $conversation['receiver_id'] : $conversation['sender_id'];
                        $profileImage = $conversation['other_profile_image'] ? $conversation['other_profile_image'] : '/images/user_pfp/default.png';
                        $previewText = mb_strlen($conversation['message']) > 25 ? mb_substr($conversation['message'], 0, 25) . '...' : $conversation['message'];
                    ?>
                        <div class="chat-app__chat-item" data-user-id="<?= $otherUserId ?>">
                            <img src="<?= htmlspecialchars($profileImage) ?>" alt="profile" class="chat-app__avatar" />
                            <div class="chat-app__chat-text">
                                <strong><?= htmlspecialchars($conversation['other_username']) ?></strong>
                                <span><?= htmlspecialchars($previewText) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>

        <main class="chat-app__main">
            <div class="chat-app__header">
                <span id="current-chat-user"></span>
                <span class="chat-app__menu-button">⋯</span>
            </div>
            <div class="chat-app__messages" id="messages-container">
                <div class="chat-app__empty-state">
                    <p>Select a conversation to start chatting</p>
                </div>
            </div>
            <div class="chat-app__input-area">
                <input class="chat-app__input" type="text" placeholder="Type a message here..." id="message-input" disabled />
                <button class="chat-app__send-button" id="send-button" disabled>➤</button>
            </div>
        </main>
    </div>
</div>

<script>
    // Store current user data for use in JavaScript
    const currentUser = <?= json_encode([
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'profile_image' => $user['profile_image'] ?? '/images/user_pfp/default.png'
                        ]) ?>;
</script>
<script src="/js/messages.js"></script>

<?php
drawFooter($user);
?>