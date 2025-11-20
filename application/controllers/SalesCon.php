<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesCon extends CI_Controller
{

    
    // Dashboard
    public function sales_dashboard()
    {
        $data['title'] = "Glassify - Dashboard";
        $data['active'] = 'dashboard';
        $data['content_view'] = 'sales_page/sales_dashboard';
        $data['page_css'] = 'sales_css/sales_dashboard.css';
        $this->load->view('sales_page/layout', $data);
    }


    // Payments
    public function sales_payments()
    {
        $data['title'] = "Glassify - Payments";
        $data['active'] = 'payments';
        $data['content_view'] = 'sales_page/sales_payments';
        $data['page_css'] = 'admin_css/admin_payments.css';
        $this->load->view('sales_page/layout', $data);
    }

    // End Users
    public function sales_endUser()
    {
        $data['title'] = "Glassify - End Users";
        $data['active'] = 'endUser';
        $data['content_view'] = 'sales_page/sales_endUser';
        $data['page_css'] = 'sales_css/sales_endUser.css';
        $this->load->view('sales_page/layout', $data);
    }

    // Orders
    public function sales_orders()
    {
        $data['title'] = "Glassify - Orders";
        $data['active'] = 'orders';
        $data['content_view'] = 'sales_page/sales_orders';
        $data['page_css'] = 'sales_css/sales_orders.css';
        $this->load->view('sales_page/layout', $data);
    }
    // Inventory
    public function sales_inventory()
    {
        $data['title'] = "Glassify - Inventory";
        $data['active'] = 'inventory';
        $data['content_view'] = 'sales_page/sales_inventory';
        $data['page_css'] = 'admin_css/admin_inventory.css';
        $this->load->view('sales_page/layout', $data);
    }

    // Products
    public function sales_products()
    {
        $data['title'] = "Glassify - Products";
        $data['active'] = 'product';
        $data['content_view'] = 'sales_page/sales_products';
        $data['page_css'] = 'admin_css/admin_product.css';
        $this->load->view('sales_page/layout', $data);
    }
    
    // Issues/Support
    public function sales_issues()
    {
        $data['title'] = "Glassify - Issues/Support";
        $data['active'] = 'issues';
        $data['content_view'] = 'sales_page/sales_issues';
        $data['page_css'] = 'sales_css/sales_issues.css';
        $this->load->view('sales_page/layout', $data);
    }


    
}