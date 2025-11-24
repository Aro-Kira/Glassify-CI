<!-- ========================= ADMIN SIDEBAR ========================= -->

<aside class="sidebar" id="sidebar">
    <div class="menu-notch" id="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>

    <!-- ========================= LOGO SECTION ========================= -->
    <div class="logo-container">
        <img src="<?php echo base_url('assets/images/img-page/logo.png'); ?>" alt="GlassWorth Builders Logo" class="logo">
    </div>

    <!-- ========================= NAVIGATION MENU ========================= -->
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo (isset($active) && $active == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo base_url('inventory-dashboard'); ?>">
                    <img src="<?php echo base_url('assets/images/img_admin/dashboard.svg'); ?>" alt="Dashboard">
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- ========================= GENERAL SECTION ========================= -->
    <div class="general-section">
        <span class="section-title">General</span>
        <ul>
            <li class="<?php echo (isset($active) && $active == 'inventory') ? 'active' : ''; ?>">
                <a href="<?php echo base_url('inventory-inventory'); ?>">
                    <img src="<?php echo base_url('assets/images/img_admin/inventory.svg'); ?>" alt="Inventory">
                    <span>Inventory</span>
                </a>
            </li>
            <li class="<?php echo (isset($active) && $active == 'product') ? 'active' : ''; ?>">
                <a href="<?php echo base_url('inventory-products'); ?>">
                    <img src="<?php echo base_url('assets/images/img_admin/products.svg'); ?>" alt="Products">
                    <span>Products</span>
                </a>
            </li>
             <li class="<?php echo (isset($active) && $active == 'reports') ? 'active' : ''; ?>">
                <a href="<?php echo base_url('inventory-reports'); ?>">
                    <img src="<?php echo base_url('assets/images/img_admin/reports.svg'); ?>" alt="Reports">
                    <span>Reports</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
