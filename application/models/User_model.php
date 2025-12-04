<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'user';
    private $addressTable = 'user_address';

    public function __construct()
    {
        parent::__construct();
    }

    // =============================
    // USER FUNCTIONS
    // =============================
    public function register($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function email_exists($email)
    {
        return $this->db->where('Email', $email)->count_all_results($this->table) > 0;
    }

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, ['Email' => $email])->row();
    }

    public function login($email)
    {
        return $this->get_by_email($email);
    }

    public function get_by_id($id)
    {
        return $this->db->where('UserID', $id)->get($this->table)->row();
    }

    public function update_user($id, $data)
    {
        return $this->db->where('UserID', $id)->update($this->table, $data);
    }

    // =============================
    // ADDRESS FUNCTIONS
    // =============================
    public function get_addresses($userID)
    {
        $this->db->where('UserID', $userID);
        $query = $this->db->get($this->addressTable);
        $addresses = $query->result();

        $result = [
            'Shipping' => null,
            'Billing' => null
        ];

        foreach ($addresses as $addr) {
            $result[$addr->AddressType] = $addr;
        }

        return $result;
    }

    public function update_address($userID, $addressType, $data)
    {
        $this->db->where(['UserID' => $userID, 'AddressType' => $addressType]);
        $exists = $this->db->count_all_results($this->addressTable, FALSE);

        if ($exists > 0) {
            return $this->db->update($this->addressTable, $data);
        } else {
            $data['UserID'] = $userID;
            $data['AddressType'] = $addressType;
            return $this->db->insert($this->addressTable, $data);
        }
    }

    // ====================================
    // ADD NEW ADDRESS (for multiple saved)
    // ====================================
    public function add_address($data)
    {
        $this->db->insert($this->addressTable, $data);
        return $this->db->insert_id();
    }

    // ====================================
    // GET USER ADDRESSES (for multiple saved)
    // ====================================


    public function get_user_addresses($userID)
{
    return $this->db
        ->where('UserID', $userID)
        ->get($this->addressTable)   // your table name user_address
        ->result();
}

}
