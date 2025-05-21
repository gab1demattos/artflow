<div id="edit-profile-modal-overlay" class="modal-overlay hidden">
    <div class="modal login" id="edit-profile-modal">
        <div class="modal-content login">
            <div class="form-container">
                <h2 id='h2-edit-profile'>Edit Profile</h2>
                <form id="edit-profile-form" class="form" method='POST' action='/actions/edit-profile-action.php' enctype="multipart/form-data">
                    <input type="text" placeholder="Name" name="name" value="<?= $user->getName() ?>" required>
                    <input type="text" placeholder="Username" name="username" value="<?= $user->getUsername() ?>" required>
                    <textarea name="bio" placeholder="Bio (optional)"><?= $user->getBio() ?></textarea>
                    <div>
                        <label for="profile_image">Profile Image:</label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*">
                    </div>
                    <div class="button-container">
                        <button type="submit" class="button long filled classic green">Save Changes</button>
                        <button type="button" id="cancel-edit-profile" class="button long outline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>