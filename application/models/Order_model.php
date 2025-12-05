<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a new order
     */
    public function create_order($order_data)
    {
        $this->db->insert('order', $order_data);
        return $this->db->insert_id();
    }

    /**
     * Save order customizations from cart items
     */
    public function save_order_customizations($order_id, $cart_items)
    {
        foreach ($cart_items as $item) {
            $customization_data = [
                'OrderID' => $order_id,
                'Product_ID' => $item->Product_ID,
                'DesignRef' => $item->DesignRef ?? null,
                'Dimensions' => $item->Dimensions ?? null,
                'GlassShape' => $item->GlassShape ?? null,
                'GlassType' => $item->GlassType ?? null,
                'GlassThickness' => $item->GlassThickness ?? null,
                'EdgeWork' => $item->EdgeWork ?? null,
                'FrameType' => $item->FrameType ?? null,
                'Engraving' => $item->Engraving ?? null,
                'Quantity' => $item->Quantity,
                'EstimatePrice' => $item->EstimatePrice ?? $item->Price ?? $item->BasePrice ?? 0
            ];
            $this->db->insert('order_customization', $customization_data);
        }
        return true;
    }

    /**
     * Get order customizations by order ID
     */
    public function get_order_customizations($order_id)
    {
        $this->db->select('oc.*, p.ProductName, p.ImageUrl');
        $this->db->from('order_customization oc');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('oc.OrderID', $order_id);
        return $this->db->get()->result();
    }

    /**
     * Get order by ID
     */
    public function get_order($order_id)
    {
        return $this->db->where('OrderID', $order_id)->get('order')->row();
    }

    /**
     * Get order with customer details
     */
    public function get_order_with_customer($order_id)
    {
        $this->db->select('o.*, u.First_Name, u.Last_Name, u.Email, u.PhoneNum');
        $this->db->from('order o');
        $this->db->join('user u', 'u.UserID = o.Customer_ID', 'left');
        $this->db->where('o.OrderID', $order_id);
        return $this->db->get()->row();
    }

    /**
     * Get orders by customer ID
     */
    public function get_customer_orders($customer_id)
    {
        return $this->db->where('Customer_ID', $customer_id)
                        ->order_by('OrderDate', 'DESC')
                        ->get('order')
                        ->result();
    }

    /**
     * Update order status
     */
    public function update_order_status($order_id, $status)
    {
        return $this->db->where('OrderID', $order_id)
                        ->update('order', ['Status' => $status]);
    }

    /**
     * Update payment status
     */
    public function update_payment_status($order_id, $status)
    {
        return $this->db->where('OrderID', $order_id)
                        ->update('order', ['PaymentStatus' => $status]);
    }

    /**
     * Get default sales rep ID (first available)
     */
    public function get_default_sales_rep()
    {
        $result = $this->db->select('UserID')
                           ->where('Role', 'Sales Representative')
                           ->limit(1)
                           ->get('user')
                           ->row();
        return $result ? $result->UserID : 2; // Default to 2 if none found
    }

    /**
     * Calculate order summary
     */
    public function calculate_order_summary($order_id)
    {
        $items = $this->get_order_customizations($order_id);
        
        $subtotal = 0;
        $total_items = 0;
        
        foreach ($items as $item) {
            $subtotal += $item->EstimatePrice * $item->Quantity;
            $total_items += $item->Quantity;
        }
        
        $shipping = $total_items * 25;
        $handling = $total_items * 10;
        $total = $subtotal + $shipping + $handling;
        
        return [
            'items' => $total_items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'handling' => $handling,
            'total' => $total
        ];
    }

    /**
     * Get all order items (purchases) for a customer
     * Joins order, order_customization, and product tables
     */
    public function get_customer_order_items($customer_id)
    {
        $this->db->select('
            oc.OrderCustomizationID as OrderItemID,
            oc.OrderID,
            oc.Product_ID,
            oc.Quantity,
            oc.EstimatePrice,
            oc.Dimensions,
            oc.GlassShape,
            oc.GlassType,
            oc.GlassThickness,
            oc.EdgeWork,
            oc.FrameType,
            oc.Engraving,
            p.ProductName,
            p.ImageUrl,
            p.Category,
            o.OrderDate,
            o.Status as OrderStatus,
            o.PaymentStatus,
            o.DeliveryAddress,
            DATE_ADD(o.OrderDate, INTERVAL 7 DAY) as DeliveryDate
        ');
        $this->db->from('order_customization oc');
        $this->db->join('`order` o', 'o.OrderID = oc.OrderID', 'left');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('o.Customer_ID', $customer_id);
        $this->db->order_by('o.OrderDate', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get completed/delivered order items for a customer
     */
    public function get_customer_delivered_items($customer_id)
    {
        $this->db->select('
            oc.OrderCustomizationID as OrderItemID,
            oc.OrderID,
            oc.Product_ID,
            oc.Quantity,
            oc.EstimatePrice,
            oc.Dimensions,
            oc.GlassShape,
            oc.GlassType,
            p.ProductName,
            p.ImageUrl,
            o.OrderDate,
            o.Status as OrderStatus,
            DATE_ADD(o.OrderDate, INTERVAL 7 DAY) as DeliveryDate
        ');
        $this->db->from('order_customization oc');
        $this->db->join('`order` o', 'o.OrderID = oc.OrderID', 'left');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('o.Customer_ID', $customer_id);
        $this->db->where('o.Status', 'Completed');
        $this->db->order_by('o.OrderDate', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get full order details for tracking page
     */
    public function get_order_tracking_details($order_id)
    {
        $this->db->select('
            o.*,
            u.First_Name,
            u.Last_Name,
            u.Email,
            u.PhoneNum,
            DATE_ADD(o.OrderDate, INTERVAL 3 DAY) as OcularDate,
            DATE_ADD(o.OrderDate, INTERVAL 7 DAY) as FabricationDate,
            DATE_ADD(o.OrderDate, INTERVAL 14 DAY) as InstallationDate,
            DATE_ADD(o.OrderDate, INTERVAL 21 DAY) as EstimatedDelivery
        ');
        $this->db->from('`order` o');
        $this->db->join('user u', 'u.UserID = o.Customer_ID', 'left');
        $this->db->where('o.OrderID', $order_id);
        
        return $this->db->get()->row();
    }

    /**
     * Get payment info for an order
     */
    public function get_order_payment($order_id)
    {
        return $this->db->where('OrderID', $order_id)->get('payment')->row();
    }

    /**
     * Get order progress steps based on status
     * Each status marks all previous steps as completed
     */
    public function get_order_progress($status)
    {
        $steps = [
            'order_placed' => false,
            'ocular_visit' => false,
            'in_fabrication' => false,
            'installed' => false,
            'completed' => false
        ];

        switch ($status) {
            case 'Completed':
                $steps['completed'] = true;
                $steps['installed'] = true;
                $steps['in_fabrication'] = true;
                $steps['ocular_visit'] = true;
                $steps['order_placed'] = true;
                break;
            case 'Ready for Installation':
                $steps['installed'] = true;
                $steps['in_fabrication'] = true;
                $steps['ocular_visit'] = true;
                $steps['order_placed'] = true;
                break;
            case 'In Fabrication':
                $steps['in_fabrication'] = true;
                $steps['ocular_visit'] = true;
                $steps['order_placed'] = true;
                break;
            case 'Approved':
                $steps['ocular_visit'] = true;
                $steps['order_placed'] = true;
                break;
            case 'Pending':
                $steps['order_placed'] = true;
                break;
            case 'Cancelled':
            case 'Returned':
                $steps['order_placed'] = true;
                break;
        }

        return $steps;
    }

    // ====================================
    // DASHBOARD FUNCTIONS
    // ====================================

    /**
     * Get orders for dashboard display with product names
     * Limits to recent orders for performance
     */
    public function get_customer_orders_with_products($customer_id, $limit = 10)
    {
        $this->db->select('
            o.OrderID,
            o.OrderDate,
            o.Status,
            o.PaymentStatus,
            oc.Product_ID,
            p.ProductName,
            p.ImageUrl
        ');
        $this->db->from('`order` o');
        $this->db->join('order_customization oc', 'oc.OrderID = o.OrderID', 'left');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('o.Customer_ID', $customer_id);
        $this->db->order_by('o.OrderDate', 'DESC');
        $this->db->group_by('o.OrderID');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Count orders by status for a customer
     */
    public function count_orders_by_status($customer_id, $status = null)
    {
        $this->db->where('Customer_ID', $customer_id);
        if ($status) {
            if (is_array($status)) {
                $this->db->where_in('Status', $status);
            } else {
                $this->db->where('Status', $status);
            }
        }
        return $this->db->count_all_results('order');
    }

    /**
     * Get the most recent order activity for a customer
     */
    public function get_recent_order_activity($customer_id)
    {
        $this->db->select('
            o.OrderID,
            o.Status,
            o.OrderDate,
            p.ProductName
        ');
        $this->db->from('`order` o');
        $this->db->join('order_customization oc', 'oc.OrderID = o.OrderID', 'left');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('o.Customer_ID', $customer_id);
        $this->db->where('o.Status !=', 'Completed');
        $this->db->order_by('o.OrderDate', 'DESC');
        $this->db->limit(1);
        
        return $this->db->get()->row();
    }

    /**
     * Get activity feed for dashboard
     * Returns recent order status changes with timestamps
     */
    public function get_activity_feed($customer_id, $limit = 5)
    {
        $this->db->select('
            o.OrderID,
            o.Status,
            o.OrderDate,
            p.ProductName
        ');
        $this->db->from('`order` o');
        $this->db->join('order_customization oc', 'oc.OrderID = o.OrderID', 'left');
        $this->db->join('product p', 'p.Product_ID = oc.Product_ID', 'left');
        $this->db->where('o.Customer_ID', $customer_id);
        $this->db->order_by('o.OrderDate', 'DESC');
        $this->db->group_by('o.OrderID');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Get the time since last update for dashboard
     */
    public function get_last_update_time($customer_id)
    {
        $this->db->select('OrderDate');
        $this->db->where('Customer_ID', $customer_id);
        $this->db->order_by('OrderDate', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get('order')->row();
        
        return $result ? $result->OrderDate : null;
    }

    /**
     * Save payment receipt for E-Wallet orders
     */
    public function save_payment_receipt($order_id, $receipt_path, $amount = 0)
    {
        // Get order details for payment record
        $order = $this->get_order_with_customer($order_id);
        if ($order && $amount <= 0) {
            $amount = $order->TotalAmount;
        }

        // Check if payment record exists
        $existing = $this->db->where('OrderID', $order_id)->get('payment')->row();
        
        if ($existing) {
            // Update existing payment record
            return $this->db->where('OrderID', $order_id)
                            ->update('payment', [
                                'ReceiptPath' => $receipt_path,
                                'Amount' => $amount,
                                'Status' => 'Pending'
                            ]);
        } else {
            // Create new payment record
            return $this->db->insert('payment', [
                'OrderID' => $order_id,
                'Amount' => $amount,
                'ReceiptPath' => $receipt_path,
                'Status' => 'Pending'
            ]);
        }
    }

    /**
     * Update order payment method
     */
    public function update_payment_method($order_id, $payment_method)
    {
        // Check if order table has PaymentMethod column, otherwise skip
        return $this->db->where('OrderID', $order_id)
                        ->update('order', ['PaymentMethod' => $payment_method]);
    }
}

