<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Issue_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Insert a new issue report
     */
    public function create_issue($data)
    {
        // Ensure Report_Date is set if not provided
        if (!isset($data['Report_Date'])) {
            $data['Report_Date'] = date('Y-m-d H:i:s');
        }
        
        // Set default Status if not provided
        if (!isset($data['Status'])) {
            $data['Status'] = 'Open';
        }
        
        // Set default Priority if not provided
        if (!isset($data['Priority'])) {
            $data['Priority'] = 'Low';
        }

        if ($this->db->insert('issuereport', $data)) {
            return $this->db->insert_id();
        } else {
            // Log error
            $error = $this->db->error();
            log_message('error', 'Issue_model::create_issue failed: ' . print_r($error, true));
            log_message('error', 'Data attempted: ' . print_r($data, true));
            return FALSE;
        }
    }

    /**
     * Get all issues (with optional filters)
     */
    public function get_all_issues($filters = [])
    {
        $this->db->select('*');
        $this->db->from('issuereport');
        
        // Filter by status
        if (isset($filters['status'])) {
            $this->db->where('Status', $filters['status']);
        } else {
            // By default, show only Open issues
            $this->db->where('Status', 'Open');
        }
        
        // Filter by priority
        if (isset($filters['priority'])) {
            $this->db->where('Priority', $filters['priority']);
        }
        
        // Filter by category
        if (isset($filters['category'])) {
            $this->db->where('Category', $filters['category']);
        }
        
        // Search by name or email
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('First_Name', $search);
            $this->db->or_like('Last_Name', $search);
            $this->db->or_like('Email', $search);
            $this->db->or_like('Category', $search);
            $this->db->or_like('Description', $search);
            $this->db->group_end();
        }
        
        // Filter by sales rep's orders (if SalesRep_ID is provided)
        // Show ALL issues, not just ones linked to orders
        // Sales reps can see all customer issues, including guest submissions
        // If you want to filter by sales rep, uncomment below:
        /*
        if (isset($filters['salesrep_id']) && !empty($filters['salesrep_id'])) {
            $this->db->join('order', 'order.OrderID = issuereport.Order_ID', 'left');
            $this->db->group_start();
            $this->db->where('order.SalesRep_ID', $filters['salesrep_id']);
            $this->db->or_where('issuereport.Order_ID IS NULL'); // Include guest issues
            $this->db->group_end();
        }
        */
        
        // Order by priority (High first) then by date (newest first)
        $this->db->order_by("FIELD(Priority, 'High', 'Medium', 'Low')", 'ASC', FALSE);
        $this->db->order_by('Report_Date', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get issue by ID
     */
    public function get_issue_by_id($issue_id)
    {
        $this->db->select('issuereport.*, 
                          order.OrderID as Order_Number,
                          customer.Customer_ID');
        $this->db->from('issuereport');
        $this->db->join('order', 'order.OrderID = issuereport.Order_ID', 'left');
        $this->db->join('customer', 'customer.Customer_ID = issuereport.Customer_ID', 'left');
        $this->db->where('issuereport.Issue_ID', $issue_id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Update issue
     */
    public function update_issue($issue_id, $data)
    {
        $this->db->where('Issue_ID', $issue_id);
        return $this->db->update('issuereport', $data);
    }

    /**
     * Mark issue as resolved
     */
    public function mark_as_resolved($issue_id)
    {
        $data = [
            'Status' => 'Resolved'
        ];
        return $this->update_issue($issue_id, $data);
    }

    /**
     * Update issue priority
     */
    public function update_priority($issue_id, $priority)
    {
        $data = [
            'Priority' => $priority
        ];
        return $this->update_issue($issue_id, $data);
    }

    /**
     * Get statistics for dashboard
     */
    public function get_issue_statistics($salesrep_id = null)
    {
        $this->db->select('Status, Priority, COUNT(*) as count');
        $this->db->from('issuereport');
        
        if ($salesrep_id) {
            $this->db->join('order', 'order.OrderID = issuereport.Order_ID');
            $this->db->where('order.SalesRep_ID', $salesrep_id);
        }
        
        $this->db->where('Status', 'Open');
        $this->db->group_by('Status, Priority');
        
        $query = $this->db->get();
        $results = $query->result();
        
        $stats = [
            'total_open' => 0,
            'high_priority' => 0,
            'medium_priority' => 0,
            'low_priority' => 0
        ];
        
        foreach ($results as $row) {
            $stats['total_open'] += $row->count;
            if ($row->Priority == 'High') {
                $stats['high_priority'] = $row->count;
            } elseif ($row->Priority == 'Medium') {
                $stats['medium_priority'] = $row->count;
            } elseif ($row->Priority == 'Low') {
                $stats['low_priority'] = $row->count;
            }
        }
        
        return $stats;
    }

    /**
     * Get issues count by status
     */
    public function get_issues_count($status = 'Open', $salesrep_id = null)
    {
        $this->db->from('issuereport');
        
        if ($salesrep_id) {
            $this->db->join('order', 'order.OrderID = issuereport.Order_ID');
            $this->db->where('order.SalesRep_ID', $salesrep_id);
        }
        
        $this->db->where('Status', $status);
        return $this->db->count_all_results();
    }
}

