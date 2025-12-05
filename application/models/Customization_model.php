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

    public function get_by_id($id)
    {
        $this->db->select('cu.*, p.ProductName, p.ImageUrl, p.Price as BasePrice');
        $this->db->from('customization cu');
        $this->db->join('product p', 'p.Product_ID = cu.Product_ID', 'left');
        $this->db->where('cu.CustomizationID', $id);
        return $this->db->get()->row();
    }

    public function update($id, $data)
    {
        return $this->db->where('CustomizationID', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        // Get the customization to delete associated design image
        $customization = $this->get_by_id($id);
        if ($customization && !empty($customization->DesignRef)) {
            $filepath = FCPATH . $customization->DesignRef;
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
        }
        return $this->db->where('CustomizationID', $id)->delete($this->table);
    }

    public function delete_multiple($ids)
    {
        if (!empty($ids)) {
            // Get customizations to delete associated design images
            $this->db->where_in('CustomizationID', $ids);
            $customizations = $this->db->get($this->table)->result();
            
            foreach ($customizations as $customization) {
                if (!empty($customization->DesignRef)) {
                    $filepath = FCPATH . $customization->DesignRef;
                    if (file_exists($filepath)) {
                        @unlink($filepath);
                    }
                }
            }
            
            return $this->db->where_in('CustomizationID', $ids)->delete($this->table);
        }
        return false;
    }

    public function get_cart_count($customer_id)
    {
        $this->db->where('Customer_ID', $customer_id);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Get customization with design image for invoice/quotation
     */
    public function get_for_invoice($customization_id)
    {
        $this->db->select('cu.*, p.ProductName, p.ImageUrl, p.Category, p.Material');
        $this->db->from('customization cu');
        $this->db->join('product p', 'p.Product_ID = cu.Product_ID', 'left');
        $this->db->where('cu.CustomizationID', $customization_id);
        return $this->db->get()->row();
    }

    /**
     * Get all customizations for a customer with design images
     */
    public function get_customer_designs($customer_id)
    {
        $this->db->select('cu.CustomizationID, cu.DesignRef, cu.Dimensions, cu.GlassShape, 
                          cu.GlassType, cu.EstimatePrice, cu.CreatedAt, p.ProductName');
        $this->db->from('customization cu');
        $this->db->join('product p', 'p.Product_ID = cu.Product_ID', 'left');
        $this->db->where('cu.Customer_ID', $customer_id);
        $this->db->where('cu.DesignRef IS NOT NULL');
        $this->db->order_by('cu.CreatedAt', 'DESC');
        return $this->db->get()->result();
    }
}
