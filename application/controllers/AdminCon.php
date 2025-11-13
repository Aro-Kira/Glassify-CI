<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminCon extends CI_Controller
{
    // Dashboard
    public function admin_dashboard()
    {
        $data['title'] = "Glassify - Dashboard";
        $data['active'] = 'dashboard';
        $data['content_view'] = 'admin_page/admin_dashboard';
        $data['page_css'] = 'admin_css/admin_dashboard.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Orders
    public function admin_orders()
    {
        $data['title'] = "Glassify - Orders";
        $data['active'] = 'orders';
        $data['content_view'] = 'admin_page/admin_orders';
        $data['page_css'] = 'admin_css/admin_orders.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Appointments
    public function admin_appointment()
    {
        $data['title'] = "Glassify - Appointments";
        $data['active'] = 'appointment';
        $data['content_view'] = 'admin_page/admin_appointment';
        $data['page_css'] = 'admin_css/admin_appointment.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Employees
    public function admin_employee()
    {
        $data['title'] = "Glassify - Employees";
        $data['active'] = 'employee';
        $data['content_view'] = 'admin_page/admin_employee';
        $data['page_css'] = 'admin_css/admin_employee.css';
        $this->load->view('admin_page/layout', $data);
    }

    // End Users
    public function admin_endUser()
    {
        $data['title'] = "Glassify - End Users";
        $data['active'] = 'endUser';
        $data['content_view'] = 'admin_page/admin_endUser';
        $data['page_css'] = 'admin_css/admin_endUser.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Inventory
    public function admin_inventory()
    {
        $data['title'] = "Glassify - Inventory";
        $data['active'] = 'inventory';
        $data['content_view'] = 'admin_page/admin_inventory';
        $data['page_css'] = 'admin_css/admin_inventory.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Products
    public function admin_product()
    {
        $data['title'] = "Glassify - Products";
        $data['active'] = 'product';
        $data['content_view'] = 'admin_page/admin_product';
        $data['page_css'] = 'admin_css/admin_product.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Payments
    public function admin_payments()
    {
        $data['title'] = "Glassify - Payments";
        $data['active'] = 'payments';
        $data['content_view'] = 'admin_page/admin_payments';
        $data['page_css'] = 'admin_css/admin_payments.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Reports
    public function admin_reports()
    {
        $data['title'] = "Glassify - Reports";
        $data['active'] = 'reports';
        $data['content_view'] = 'admin_page/admin_reports';
        $data['page_css'] = 'admin_css/admin_reports.css';
        $this->load->view('admin_page/layout', $data);
    }

    // Account
    public function admin_account()
    {
        $data['title'] = "Glassify - Account Management";
        $data['active'] = 'account';
        $data['content_view'] = 'admin_page/admin_account';
        $data['page_css'] = 'admin_css/admin_accounts.css';
        $this->load->view('admin_page/layout', $data);
    }
}
