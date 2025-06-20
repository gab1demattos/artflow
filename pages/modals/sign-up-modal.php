<div id="signup-modal-overlay" class="modal-overlay hidden">
    <div class="modal login" id="signup-modal">
        <div class="modal-content login">
            <div class="form-container">
                <h2 id='h2-signup'>Create Account</h2>
                <form id="signup-form" class="form" method="POST" action="../../actions/login/signup-action.php">
                    <?php
                    require_once(__DIR__ . '/../../database/security/csrf.php');
                    echo CSRF::getTokenField('signup_csrf_token');
                    ?>
                    <input type="text" placeholder="Name" name="name" required>
                    <input type="text" placeholder="Username" name="username" required>
                    <input type="email" placeholder="Email" name="email" required>
                    <div class="password-input-container">
                        <input type="password" placeholder="Password" name="password" required id="password">
                        <button type="button" class="toggle-password">
                            <i class="material-icons" id="togglePassword">visibility_off</i>
                        </button>
                    </div>
                    <div class="password-input-container">
                        <input type="password" placeholder="Confirm Password" name="confirm_password" required id="confirm_password">
                        <button type="button" class="toggle-password">
                            <i class="material-icons" id="toggleConfirmPassword">visibility_off</i>
                        </button>
                    </div>
                    <div class="button-container">
                        <button type="submit" id='sign-up-submit' class="button long filled classic">Sign Up</button>
                        <button type="button" id="sign-in" class="button long outline">Sign In</button>
                    </div>
                </form>
            </div>
            <div class="modal-right">
                <img src="/images/modals/sign_up/sign-up-image.png" alt="Illustration">
            </div>
        </div>
    </div>
</div>