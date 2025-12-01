<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'user';

    public function __construct() {
        parent::__construct();
    }

    public function register($data) {
        return $this->db->insert($this->table, $data);
    }

    public function email_exists($email) {
        return $this->db->where('Email', $email)->count_all_results($this->table) > 0;
    }

    public function get_by_email($email) {
        return $this->db->get_where($this->table, ['Email' => $email])->row();
    }

    // ✅ REQUIRED BY Auth.php
    public function login($email)
    {
        return $this->get_by_email($email);
    }
}
