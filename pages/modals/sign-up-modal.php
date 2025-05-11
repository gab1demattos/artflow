<div id="signup-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="signup-modal">
        <div class="modal-content">
            <div class="form-container">
                <h2 id='h2-signup'>Create Account</h2>
                <form id="signup-form" class="form" method='POST' action='/actions/signup-action.php'>
                    <input type="text" placeholder="Name" name="name" required>
                    <input type="text" placeholder="Username" name="username" required>
                    <input type="email" placeholder="Email" name="email" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <input type="password" placeholder="Confirm Password" name="confirm_password" required>
                    <div class="button-container">
                        <button type="submit" id='sign-up-submit' class="button long filled classic">Sign Up</button>
                        <button type="button" id="sign-in" class="button long outline">Sign In</button>
                    </div>
                </form>
            </div>
            <div class="modal-right">
                <img src="../images/modals/sign_up/sign-up-image.png" alt="Illustration">
            </div>
        </div>
    </div>
</div>
