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
                                <label for="profile_image"yellow class="file-label">Choose file</label>
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

    document.getElementById('delete-image-btn').addEventListener('click', function() {
        document.getElementById('profile-preview').src = '/images/user_pfp/default.png';
        document.getElementById('profile_image').value = '';
        document.getElementById('reset_profile_image').value = '1'; // Set the hidden input to indicate reset
        // Update label to show default text
        const fileLabel = document.querySelector('.file-label');
        if (fileLabel) {
            fileLabel.textContent = 'Choose file';
        }
    });

    document.getElementById('profile-preview').addEventListener('load', function() {
        const deleteBtn = document.getElementById('delete-image-btn');
        if (this.src !== '/images/user_pfp/default.png') {
            deleteBtn.style.display = 'block';
        } else {
            deleteBtn.style.display = 'none';
        }
    });
</script>