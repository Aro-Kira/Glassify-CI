<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/auth/login_style.css'); ?>">

<?php if ($this->session->flashdata('error')): ?>
  <p style="color: red; text-align:center; margin-bottom:10px;">
    <?php echo $this->session->flashdata('error'); ?>
  </p>
<?php endif; ?>

<?php if ($this->session->flashdata('info')): ?>
  <p style="color: #007bff; text-align:center; margin-bottom:10px;">
    <?php echo $this->session->flashdata('info'); ?>
  </p>
<?php endif; ?>

<section class="login-section">
  <div class="login-container">

    <!-- Left Panel -->
    <div class="login-left">
      <div class="login-logo">
        <img src="<?php echo base_url('assets/images/img-page/logo.png'); ?>" alt="GlassWorth Logo">
      </div>
      <h2 class="login-brand">Glassify</h2>
      <p class="login-description">
        <span class="highlight">Inventory</span> Access Only.<br>
        Manage <span class="highlight">Users</span>, Orders, and Settings.
      </p>
      <div class="login-user-icon">
        <img src="<?php echo base_url('assets/images/img-page/mdi_shield-account.svg'); ?>" alt="Inventory-icon">
      </div>
    </div>

    <!-- Right Panel -->
    <div class="login-right">
      <h3 class="login-title">Inventory Sign In</h3>

      <!-- ✅ Inventory Login Form -->
        <form action="<?= base_url('auth/process_login') ?>" method="post">
        <input type="hidden" name="required_role" value="Inventory Officer">


        <?php
        $pending_email = $this->session->tempdata('pending_inventory_email');
        ?>

        <div class="login-input-group">
          <label for="email">Inventory Email</label>
          <div class="login-input-row">
            <img src="<?php echo base_url('assets/images/img-page/ic_outline-email.svg'); ?>" alt="Email Icon"
              class="login-input-icon">
            <input type="email" id="email" name="email" placeholder="Enter your inventory email"
              value="<?= $pending_email ?? '' ?>" required>
          </div>
        </div>

        <div class="login-input-group">
          <label for="password">Password</label>
          <div class="login-input-row">
            <img src="<?php echo base_url('assets/images/img-page/solar_password-outline.svg'); ?>" alt="Password Icon"
              class="login-input-icon">
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
        </div>

        <button type="submit" class="login-btn">Login as Inventory</button>

        <div class="login-options">
          <label><input type="checkbox"> Remember Me</label>
          <a href="#">Forgot Password?</a>
        </div>

        <p class="login-register">
          <a href="<?php echo base_url('login'); ?>">← Back to User Login</a>
        </p>
      </form>

    </div>
  </div>
</section>
