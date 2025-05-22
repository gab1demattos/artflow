<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

drawHeader($user);
?>
<div id='messages-page'>
    <div class="chat-app__container">
        <aside class="chat-app__sidebar">
            <h1 class="chat-app__title">chats</h1>
            <div class="chat-app__search-bar">
                <input class="chat-app__search-input" type="text" placeholder="Search here..." />
                <img class="chat-app__search-icon" src="/images/logos/search.png" alt="search icon"/>
            </div>
            <div class="chat-app__chat-list">
                <div class="chat-app__chat-item">
                    <img src="https://i.pravatar.cc/40" alt="profile" class="chat-app__avatar" />
                    <div class="chat-app__chat-text">
                        <strong>Maria Martinez</strong>
                        <span>I'm interested in your service. Ca...</span>
                    </div>
                </div>
                <!-- Repeat items -->
                <div class="chat-app__chat-item">
                    <img src="https://i.pravatar.cc/40" alt="profile" class="chat-app__avatar" />
                    <div class="chat-app__chat-text">
                        <strong>Maria Martinez</strong>
                        <span>I'm interested in your service. Ca...</span>
                    </div>
                </div>
            </div>
        </aside>

        <main class="chat-app__main">
            <div class="chat-app__header">
                <span class="chat-app__menu-button">⋯</span>
            </div>
            <div class="chat-app__messages">
                <div class="chat-app__timestamp">Today, 3:43 pm</div>
                <div class="chat-app__message chat-app__message--sent">Hi, I'm interested in your service.</div>
                <div class="chat-app__message chat-app__message--received">Thank you! I'd be happy to help.</div>
            </div>
            <div class="chat-app__input-area">
                <input class="chat-app__input" type="text" placeholder="Type a message here..." />
                <button class="chat-app__send-button">➤</button>
            </div>
        </main>
    </div>
</div>

<?php
drawFooter($user);
?>