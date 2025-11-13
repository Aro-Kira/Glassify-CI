<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/auth/register_style.css'); ?>">

<section class="register-section">
    <div class="register-container">

        <!-- Left Panel -->
        <div class="register-left">
            <div class="register-logo">
                <img src="<?php echo base_url('assets/images/img-page/logo.png'); ?>" alt="Glassify Logo">
            </div>
            <h2 class="register-brand">Glassify</h2>
            <p class="register-description">
                <span class="highlight">Design</span> Your Glass Project.
                Get <span class="highlight">Instant</span> Quotes.
                <span class="highlight">Order</span> Online.
            </p>
            <div class="login-user-icon">
                <img src="<?php echo base_url('assets/images/img-page/mdi_account-outline.svg'); ?>" alt="account-icon">
            </div>
        </div>

        <!-- Right Panel -->
        <div class="register-right">
            <h3 class="register-title">Create an Account</h3>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php elseif ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <?php echo form_open(base_url('auth/process_register'), ['class' => 'register-form']); ?>

                <div class="register-input-group">
                    <label for="firstName">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" id="firstName" placeholder="Enter your first name" required>
                </div>

                <div class="register-input-group">
                    <label for="middleInitial">Middle Initial (optional)</label>
                    <input type="text" name="middle_initial" id="middleInitial" placeholder="Enter your middle initial">
                </div>

                <div class="register-input-group">
                    <label for="surname">Surname <span class="required">*</span></label>
                    <input type="text" name="surname" id="surname" placeholder="Enter your surname" required>
                </div>

                <div class="register-input-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <div class="register-input-group password-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <div class="register-input-row">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password"><i class="fa fa-eye"></i></button>

                    </div>
                </div>

                <div class="register-input-group password-group">
                    <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                    <div class="register-input-row">
                        <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm your password" required>
                        <button type="button" class="toggle-password"><i class="fa fa-eye"></i></button>

                    </div>
                </div>

                <div class="register-input-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" name="phone" id="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="register-options">
                    <label><input type="checkbox" required> I agree to Glassify’s 
                        <a href="<?php echo base_url('terms'); ?>">Terms and Conditions</a>
                    </label>
                    <a href="#">Need Help?</a>
                </div>

                <button type="submit" class="register-btn">Sign Up</button>

            <?php echo form_close(); ?>
        </div>

    </div>
</section>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Select all toggle-password buttons
    const toggles = document.querySelectorAll(".toggle-password");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", function() {
            // Find the related password input (the one before the button)
            const input = this.previousElementSibling;
            if (!input) return;

            // Toggle between password and text
            const isHidden = input.type === "password";
            input.type = isHidden ? "text" : "password";

            // Optional: change button icon/text
           this.innerHTML = isHidden ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';

        });
    });
});
</script>
