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
}

