<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InventCon extends CI_Controller
{
    public function inventory_dashboard()
    {
        $data['title'] = "Glassify - Inventory Dashboard";
        $data['active'] = 'dashboard';
        $data['content_view'] = 'inventory_page/inventory_dashboard';
        $data['page_css'] = 'inventory_css/inventory_dashboard.css';
        $this->load->view('inventory_page/layout', $data);
    }

    public function inventory_products()
    {
        $data['title'] = "Glassify - Inventory Products";
        $data['active'] = 'product';
        $data['content_view'] = 'inventory_page/inventory_products';
        $data['page_css'] = 'admin_css/admin_product.css';
        $this->load->view('inventory_page/layout', $data);
    }

    public function inventory_inventory()
    {
        $data['title'] = "Glassify - Inventory Management";
        $data['active'] = 'inventory';
        $data['content_view'] = 'inventory_page/inventory_inventory';
        $data['page_css'] = 'inventory_css/inventory_inventory.css';
        $this->load->view('inventory_page/layout', $data);
    }

    public function inventory_account()
    {
        $data['title'] = "Glassify - Inventory Account";
        $data['active'] = 'account';
        $data['content_view'] = 'inventory_page/inventory_account';
        $data['page_css'] = 'admin_css/admin_accounts.css';
        $this->load->view('inventory_page/layout', $data);
    }

    public function inventory_reports()
    {
        $data['title'] = "Glassify - Inventory Reports";
        $data['active'] = 'reports';
        $data['content_view'] = 'inventory_page/inventory_reports';
        $data['page_css'] = 'inventory_css/inventory_reports.css';
        $this->load->view('inventory_page/layout', $data);
    }

    public function inventory_notif()
    {
        $data['title'] = "Glassify - Inventory Notifications";
        $data['active'] = 'inventory_notifications';
        $data['content_view'] = 'inventory_page/inventory_notif';
        $data['page_css'] = 'admin_css/admin_notif.css';
        $this->load->view('inventory_page/layout', $data);
    }
}
