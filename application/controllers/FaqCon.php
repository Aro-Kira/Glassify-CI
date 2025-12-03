<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FaqCon extends CI_Controller {

      /* 
======================================
=============FAQ Directory============
======================================
 */

 public function faq() {
        $data['title'] = "Glassify - FAQ";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq', $data);
        $this->load->view('includes/footer');
    }

    public function faq_ordering() {
        $data['title'] = "Glassify - FAQ Ordering & Product Customization";

        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_ordering', $data);
        $this->load->view('includes/footer');
      
    }
      public function faq_payment() {
        $data['title'] = "Glassify - FAQ Payments";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_payments', $data);
        $this->load->view('includes/footer');
    }
    public function faq_shipping() {
        $data['title'] = "Glassify - FAQ Shipping & Installation";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_shipping', $data);
        $this->load->view('includes/footer');
    }
    public function faq_warranty() {
        $data['title'] = "Glassify - FAQ Warranty";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_warranty', $data);
        $this->load->view('includes/footer');
    }
     public function faq_pricing() {
        $data['title'] = "Glassify - FAQ Pricing & Quotations";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_pricing', $data);
        $this->load->view('includes/footer');
    }
    public function faq_account() {
        $data['title'] = "Glassify - FAQ Account";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_account', $data);
        $this->load->view('includes/footer');
    }

    public function faq_report() {
        $this->load->helper('form'); // Load form helper for any form-related functions
        $data['title'] = "Glassify - Report Issue";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/report_issue', $data);
        $this->load->view('includes/footer');
    }

    /**
     * Process issue report submission
     */
    public function submit_issue() {
        $this->load->library(['form_validation', 'session']);
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('Issue_model');

        // Validation rules
        $this->form_validation->set_rules('first-name', 'First Name', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('last-name', 'Last Name', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|max_length[100]');
        $this->form_validation->set_rules('contact-number', 'Contact Number', 'required|trim|max_length[13]');
        $this->form_validation->set_rules('order-id', 'Order ID', 'trim'); // Made optional
        $this->form_validation->set_rules('issue-category', 'Issue Category', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required|trim|min_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(base_url('report-issue'));
            return;
        }

        // Get form data
        $first_name = $this->input->post('first-name');
        $last_name = $this->input->post('last-name');
        $email = $this->input->post('email');
        $phone = $this->input->post('contact-number');
        $order_id_input = $this->input->post('order-id');
        $category = $this->input->post('issue-category');
        $description = $this->input->post('description');

        // Handle Order ID - remove #G prefix if present, convert to integer
        $order_id_clean = preg_replace('/[^0-9]/', '', $order_id_input);
        $order_id = (int)$order_id_clean;

        // Get Customer_ID if logged in
        $user_id = $this->session->userdata('user_id');
        $customer_id = NULL;
        
        if ($user_id) {
            // User is logged in - find their Customer_ID from customer table
            $this->db->select('Customer_ID');
            $this->db->from('customer');
            $this->db->where('UserID', $user_id);
            $customer = $this->db->get()->row();
            
            if ($customer) {
                $customer_id = $customer->Customer_ID;
            } else {
                // User exists but no customer record - try to create one or use NULL
                // For now, use NULL (guest submission)
                $customer_id = NULL;
            }
        } else {
            // Guest - try to find if email exists in customer table
            $this->db->select('customer.Customer_ID');
            $this->db->from('customer');
            $this->db->join('user', 'user.UserID = customer.UserID');
            $this->db->where('user.Email', $email);
            $customer = $this->db->get()->row();
            $customer_id = $customer ? $customer->Customer_ID : NULL;
        }

        // Verify Order ID exists (if provided)
        if ($order_id > 0) {
            $order_exists = $this->db->where('OrderID', $order_id)->get('order')->row();
            if (!$order_exists) {
                // Order doesn't exist, set to NULL for guest submission
                $order_id = NULL;
            }
        } else {
            $order_id = NULL; // No order ID provided
        }

        // Map form categories to database categories
        $category_map = [
            'Order Issue' => 'Order Issue',
            'Payment Issue' => 'Payment Issue',
            'Delivery Issue' => 'Delivery Issue',
            'General Inquiry' => 'General Inquiry',
            'Installation Problems' => 'Installation Problems',
            'Product Defect/Damage' => 'Product Defect/Damage',
            'Measurement/Design Problems' => 'Measurement/Design Problems',
            'Billing/Payment Questions' => 'Billing/Payment Questions',
            'Other' => 'Other'
        ];
        
        $db_category = isset($category_map[$category]) ? $category_map[$category] : 'Other';

        // Prepare issue data
        // Use NULL instead of 0 for guest submissions to avoid foreign key issues
        $issue_data = [
            'First_Name' => $first_name,
            'Last_Name' => $last_name,
            'Email' => $email,
            'PhoneNum' => $phone,
            'Category' => $db_category,
            'Description' => $description,
            'Status' => 'Open',
            'Priority' => 'Low',
            'Report_Date' => date('Y-m-d H:i:s')
        ];
        
        // Only set Customer_ID and Order_ID if they have valid values
        if ($customer_id !== NULL && $customer_id > 0) {
            $issue_data['Customer_ID'] = $customer_id;
        }
        
        if ($order_id !== NULL && $order_id > 0) {
            $issue_data['Order_ID'] = $order_id;
        }

        // Insert issue
        $issue_id = $this->Issue_model->create_issue($issue_data);

        if ($issue_id) {
            $this->session->set_flashdata('success', 'Your issue has been submitted successfully. Ticket ID: #TC-' . str_pad($issue_id, 2, '0', STR_PAD_LEFT));
            redirect(base_url('report-issue'));
        } else {
            // Get database error for debugging
            $db_error = $this->db->error();
            $error_message = 'Failed to submit issue. ';
            
            if (!empty($db_error['message'])) {
                $error_message .= 'Database Error: ' . $db_error['message'];
                log_message('error', 'Issue submission failed: ' . $db_error['message']);
                log_message('error', 'Issue data: ' . print_r($issue_data, true));
            } else {
                $error_message .= 'Please check all fields and try again.';
            }
            
            $this->session->set_flashdata('error', $error_message);
            redirect(base_url('report-issue'));
        }
    }









}
