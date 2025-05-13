<div id="signin-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="signin-modal">
        <div class="modal-content">
            <div class="modal-right">
                <img src="../images/modals/sign_in/sign-in-image.png" alt="Illustration">
            </div>
            <div class="form-container">
                <h2 id='h2-signin'>Welcome back!</h2>
                <form id="signin-form" class="form" method='POST' action='/actions/signin-action.php'>
                    <input type="email" placeholder="Email" name="email" required>
                    <div class="password-input-container">
                        <input type="password" placeholder="Password" name="password" required>
                        <button type="button" class="toggle-password">
                            <i class="material-icons" id="toggleConfirmPassword">visibility_off</i>
                        </button>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="button long filled classic yellow">Sign In</button>
                        <button type="button" id="sign-up" class="button long outline yellow">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>