<div id="edit-profile-modal-overlay" class="modal-overlay hidden">
    <div class="modal" id="edit-profile-modal">
        <div class="modal-content" id='edit-profile-modal-content'>
            <div class="form-container">
                <h2 id='h2-edit-profile'>Edit Profile</h2>
                <form id="edit-profile-form" class="form" method='POST' action='/actions/edit-profile-action.php' enctype="multipart/form-data">
                    <input type="text" placeholder="Name" name="name" value="<?= $user->getName() ?>" required>
                    <input type="text" placeholder="Username" name="username" value="<?= $user->getUsername() ?>" required>
                    <textarea name="bio" placeholder="Bio (optional)"><?= $user->getBio() ?></textarea>
                    <div>
                        <label>Profile Image:</label>
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <div id="profile-preview-container">
                                <img id="profile-preview" src="<?= $user->getProfileImage() ?? '/images/user_pfp/default.png' ?>" alt="Profile preview">
                            </div>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*">
                            <label for="profile_image" class="file-label">Choose file</label>
                        </div>
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

<script>
    document.getElementById('profile_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            // Update label to show selected filename
            const fileLabel = document.querySelector('.file-label');
            if (fileLabel) {
                const fileName = file.name.length > 15 ? file.name.substring(0, 12) + '...' : file.name;
                fileLabel.textContent = fileName;
            }
        }
    });
</script>