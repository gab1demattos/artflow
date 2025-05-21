<div id="edit-profile-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="edit-profile-modal">
        <div class="modal-content" id='edit-profile-modal-content'>
            <div class="form-container">
                <h2 id='h2-edit-profile'>Edit Profile</h2>
                <form id="edit-profile-form" class="form" method='POST' action='/actions/edit-profile-action.php' enctype="multipart/form-data">
                    <div class="form-layout">
                        <div class="form-left-column">
                            <label for="name">Name</label>
                            <input type="text" placeholder="Name" id="name" name="name" value="<?= $user->getName() ?>" required>
                            
                            <label for="username">Username</label>
                            <input type="text" placeholder="Username" id="username" name="username" value="<?= $user->getUsername() ?>" required>
                            
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" placeholder="Bio (optional)"><?= $user->getBio() ?></textarea>
                        </div>
                        <div class="form-right-column">
                            <div id="profile-preview-container">
                                <img id="profile-preview" src="<?= $user->getProfileImage() ?? '/images/user_pfp/default.png' ?>" alt="Profile preview">
                            </div>
                            <div class="file-input-container">
                                <input type="file" name="profile_image" id="profile_image" accept="image/*">
                                <label for="profile_image" class="file-label">Choose file</label>
                                <button type="button" class="delete-image-btn" id="delete-image-btn">🗑️</button>
                                <input type="hidden" name="reset_profile_image" id="reset_profile_image" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="button hovering long filled orange">Save Changes</button>
                        <button type="button" id="cancel-edit-profile" class="button long outline orange">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/js/profile.js"></script>