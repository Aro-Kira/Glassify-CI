<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customization_model extends CI_Model
{
    protected $table = 'customization';

    public function __construct() {
        parent::__construct();
    }

    public function add_customization($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // returns the new customization ID
    }

    public function delete_customization($customization_id) {
    $this->db->where('CustomizationID', $customization_id);
    return $this->db->delete($this->table);
}

public function delete_multiple($ids = [])
{
    if (empty($ids)) return false;

    $this->db->where_in('CustomizationID', $ids);
    return $this->db->delete($this->table);
}


}
