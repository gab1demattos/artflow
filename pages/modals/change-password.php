<?php
require_once(__DIR__ . '/../../database/session.php');
?>

<div id="change-password-modal" class="modal" style="display: none; position: fixed; z-index: 1100; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div class="modal-content tiny-modal">
        <div class="modal-header">
            <h3>Password Change</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="change-password-form" action="../../actions/change-password-action.php" method="post">
                <div class="form-group">
                    <label for="old-password">Old Password</label>
                    <input type="password" id="old-password" name="old-password" required>
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn-primary">Confirm</button>
                    <button type="button" class="btn-secondary cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('#change-password-modal .cancel-modal').addEventListener('click', function() {
        document.getElementById('change-password-modal').style.display = 'none';
    });

    document.querySelector('#change-password-modal .close').addEventListener('click', function() {
        document.getElementById('change-password-modal').style.display = 'none';
    });
</script>