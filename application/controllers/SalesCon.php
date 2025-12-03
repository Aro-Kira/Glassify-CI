<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SalesCon extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Issue_model');
        
        // Check if user is logged in and has Sales Representative role
        if (!$this->session->userdata('is_logged_in') || $this->session->userdata('user_role') !== 'Sales Representative') {
            $this->session->set_flashdata('error', 'Access denied. You must be logged in as a Sales Representative.');
            redirect(base_url('SlsLog'));
        }
    }
    
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

    public function sales_account()
    {
        $data['title'] = "Glassify - Account Settings";
        $data['active'] = 'account';
        $data['content_view'] = 'sales_page/sales_account';
        $data['page_css'] = 'sales_css/sales_account.css';
        $this->load->view('sales_page/layout', $data);
    }

    // ===================== ISSUE/SUPPORT API ENDPOINTS =====================

    /**
     * Get all issues (AJAX endpoint)
     */
    public function get_issues_ajax()
    {
        // Don't filter by salesrep_id - show ALL issues to all sales reps
        // Sales reps can see all customer issues, including guest submissions
        $filters = [
            'status' => $this->input->get('status') ?: 'Open',
            'priority' => $this->input->get('priority'),
            'category' => $this->input->get('category'),
            'search' => $this->input->get('search')
            // Removed salesrep_id filter to show all issues
        ];
        
        $issues = $this->Issue_model->get_all_issues($filters);
        
        // Format issues for frontend
        $formatted_issues = [];
        foreach ($issues as $issue) {
            $formatted_issues[] = [
                'issue_id' => $issue->Issue_ID,
                'ticket_id' => '#TC-' . str_pad($issue->Issue_ID, 2, '0', STR_PAD_LEFT),
                'category' => $issue->Category,
                'priority' => $issue->Priority,
                'email' => $issue->Email,
                'first_name' => $issue->First_Name,
                'last_name' => $issue->Last_Name,
                'status' => $issue->Status,
                'report_date' => $issue->Report_Date
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'issues' => $formatted_issues,
            'count' => count($formatted_issues)
        ]);
    }

    /**
     * Get issue details by ID (AJAX endpoint)
     */
    public function get_issue_details_ajax($issue_id)
    {
        $issue = $this->Issue_model->get_issue_by_id($issue_id);
        
        if (!$issue) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Issue not found'
            ]);
            return;
        }
        
        // Format order ID
        $order_id_display = $issue->Order_ID > 0 ? '#G' . str_pad($issue->Order_ID, 4, '0', STR_PAD_LEFT) : 'N/A';
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'issue' => [
                'issue_id' => $issue->Issue_ID,
                'ticket_id' => '#TC-' . str_pad($issue->Issue_ID, 2, '0', STR_PAD_LEFT),
                'first_name' => $issue->First_Name,
                'last_name' => $issue->Last_Name,
                'email' => $issue->Email,
                'phone' => $issue->PhoneNum,
                'order_id' => $order_id_display,
                'order_id_raw' => $issue->Order_ID,
                'category' => $issue->Category,
                'priority' => $issue->Priority,
                'description' => $issue->Description,
                'status' => $issue->Status,
                'report_date' => $issue->Report_Date
            ]
        ]);
    }

    /**
     * Mark issue as resolved (AJAX endpoint)
     */
    public function mark_resolved_ajax()
    {
        $issue_id = $this->input->post('issue_id');
        
        if (!$issue_id) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Issue ID is required'
            ]);
            return;
        }
        
        $result = $this->Issue_model->mark_as_resolved($issue_id);
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Issue marked as resolved'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update issue'
            ]);
        }
    }

    /**
     * Update issue priority (AJAX endpoint)
     */
    public function update_priority_ajax()
    {
        $issue_id = $this->input->post('issue_id');
        $priority = $this->input->post('priority');
        
        if (!$issue_id || !in_array($priority, ['Low', 'Medium', 'High'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request'
            ]);
            return;
        }
        
        $result = $this->Issue_model->update_priority($issue_id, $priority);
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Priority updated successfully'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update priority'
            ]);
        }
    }

    /**
     * Get issue statistics (AJAX endpoint)
     */
    public function get_issue_stats_ajax()
    {
        $salesrep_id = $this->session->userdata('user_id');
        $stats = $this->Issue_model->get_issue_statistics($salesrep_id);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'stats' => $stats
        ]);
    }

    
}