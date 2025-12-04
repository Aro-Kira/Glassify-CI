<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customization_model extends CI_Model
{
    protected $table = "customization";

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_customer_items($customer_id)
    {
        $this->db->select('cu.*, p.ProductName, p.ImageUrl');
        $this->db->from('customization cu');
        $this->db->join('product p', 'p.Product_ID = cu.Product_ID', 'left');
        $this->db->where('cu.Customer_ID', $customer_id);
        return $this->db->get()->result();
    }

    public function update($id, $data)
    {
        return $this->db->where('CustomizationID', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('CustomizationID', $id)->delete($this->table);
    }

    public function get_cart_count($customer_id)
    {
        $this->db->where('Customer_ID', $customer_id);
        return $this->db->count_all_results($this->table);
    }
}
