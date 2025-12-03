<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/user/profile.css'); ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<body>
    <main class="account-settings">
        <h2 class="settings-title">Account Settings</h2>
        <section class="settings-container">

            <!-- Left: Form -->
            <section class="settings-form">

                <form id="accountForm">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname"
                        value="<?= isset($user) ? $user->First_Name : '' ?>">

                    <label for="middlename">Middle Name</label>
                    <input type="text" id="middlename" name="middlename"
                        value="<?= isset($user) ? $user->Middle_Name : '' ?>">

                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" value="<?= isset($user) ? $user->Last_Name : '' ?>">

                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= isset($user) ? $user->Email : '' ?>">

                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= isset($user) ? $user->PhoneNum : '' ?>">

                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" disabled placeholder="Not stored in DB">

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">

                    <div class="form-buttons">
                        <button type="button" class="btn-cancel">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>

            </section>

            <!-- Right: Profile Image -->
            <section class="settings-photo">
                <img src="<?= isset($user->ImageUrl) ? base_url($user->ImageUrl) : base_url('assets/img/pfp.png') ?>"
                    class="profile-img" id="profilePreview">

                <div class="photo-buttons">
                    <button type="button" class="btn-photo" id="changePhotoBtn">Change Photo</button>
                    <input type="file" id="uploadPhoto" accept="image/*" title="Upload Profile Photo">
                    <button class="btn-delete">Delete Photo</button>
                </div>
            </section>


        </section>


    </main>


    <script>
        document.getElementById("accountForm").addEventListener("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("<?= base_url('UserCon/update_profile'); ?>", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    alert(data.status === 'success' ? "Profile Updated!" : "Update Failed");
                });
        });

        // Upload Photo
        document.getElementById("uploadPhoto").addEventListener("change", function () {
            let formData = new FormData();
            formData.append('photo', this.files[0]);

            fetch("<?= base_url('UserCon/upload_photo'); ?>", {
                method: "POST",
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.querySelector('.profile-img').src = data.image;
                    } else {
                        alert(data.message);
                    }
                });
        });

    </script>


    <script>

        $("#accountForm").on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?= base_url('UserCon/update_profile') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        alert("Profile updated successfully!");
                    } else {
                        alert("Failed to update: " + res.message);
                    }
                },
                error: function () {
                    alert("Error sending request.");
                }
            });
        });

        // When user uploads a photo
        $("#uploadPhoto").on("change", function () {
            let formData = new FormData();
            formData.append("photo", this.files[0]);

            $.ajax({
                url: "<?= base_url('UserCon/upload_photo') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        $("#profilePreview").attr("src", res.image); // Update preview
                        alert("Profile photo updated!");
                    } else {
                        alert(res.message);
                    }
                },
                error: function () {
                    alert("Failed to upload image.");
                }
            });
        });

        $("#changePhotoBtn").click(function () {
            $("#uploadPhoto").click();
        });


    </script>


</body>