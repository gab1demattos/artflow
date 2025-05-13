<div id="signin-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="signin-modal">
        <div class="modal-content">
            <div class="modal-right">
                <img src="../images/modals/sign_in/sign-in-image.png" alt="Illustration">
            </div>
            <div class="form-container">
                <h2 id='h2-signin'>Welcome back!</h2>
                <form id="signin-form" class="form" method='POST' action='/actions/signin-action.php'>
                    <input type="text" placeholder="Username" name="username" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <div class="button-container">
                        <button type="submit" class="button long filled classic yellow">Sign In</button>
                        <button type="button" id="sign-up" class="button long outline yellow">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>