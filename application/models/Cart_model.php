<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ===================== SAVE CUSTOMIZATION =====================
    public function save_customization($data)
    {
        $this->db->insert('customization', $data);
        return $this->db->insert_id(); // returns CustomizationID
    }

   // ===================== GET CART ITEMS =====================
public function get_cart_items($customer_id)
{
    $this->db->select('
        c.Cart_ID,
        c.Product_ID,
        c.CustomizationID,
        c.Quantity,
        p.ProductName,
        p.Price as BasePrice,
        p.ImageUrl,
        cu.DesignRef,
        cu.Dimensions,
        cu.GlassShape,
        cu.GlassType,
        cu.GlassThickness,
        cu.EdgeWork,
        cu.FrameType,
        cu.Engraving,
        cu.EstimatePrice,
        IF(cu.EstimatePrice IS NOT NULL AND cu.EstimatePrice > 0, cu.EstimatePrice, p.Price) as Price
    ');

    $this->db->from('cart c');
    $this->db->join('product p', 'p.Product_ID = c.Product_ID', 'left');
    $this->db->join('customization cu', 'cu.CustomizationID = c.CustomizationID', 'left');
    $this->db->where('c.Customer_ID', $customer_id);

    $query = $this->db->get();
    return $query->result();
}




    // ===================== ADD TO CART =====================
    public function add_to_cart($data)
    {
        // Check if product already in cart
        $this->db->where('Customer_ID', $data['Customer_ID']);
        $this->db->where('Product_ID', $data['Product_ID']);
        $this->db->where('CustomizationID', $data['CustomizationID']);
        $query = $this->db->get('cart');

        if ($query->num_rows() > 0) {
            // Update quantity
            $row = $query->row();
            $this->db->where('Cart_ID', $row->Cart_ID);
            $this->db->update('cart', ['Quantity' => $row->Quantity + $data['Quantity']]);
        } else {
            $this->db->insert('cart', $data);
        }
        return true;
    }

    // ===================== REMOVE ITEM =====================
    public function remove_item($cart_id)
    {
        $this->db->where('Cart_ID', $cart_id);
        return $this->db->delete('cart');
    }

    // ===================== CLEAR CART =====================
    public function clear_cart($customer_id)
    {
        $this->db->where('Customer_ID', $customer_id);
        return $this->db->delete('cart');
    }
}
