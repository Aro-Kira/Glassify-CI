<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Make sure database is loaded
    }
public function get_products() {
    return $this->db->order_by('DateAdded', 'DESC')->get('product')->result();
}

public function insert_product($data) {
    return $this->db->insert('product', $data);
}

public function delete_product($id) {
    return $this->db->where('Product_ID', $id)->delete('product');
}

public function update_product($id, $data) {
    return $this->db->where('Product_ID', $id)->update('product', $data);
}

public function get_product($id) {
    return $this->db->where('Product_ID', $id)->get('product')->row();
}

}
