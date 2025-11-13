<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EndUserCon extends CI_Controller {

    private $data_file;

    public function __construct() {
        parent::__construct();
        // JSON file path inside application/data/
        $this->data_file = APPPATH . 'data/end-users.json';
    }

    private function read_data() {
        if (!file_exists($this->data_file)) return [];
        $json = file_get_contents($this->data_file);
        return json_decode($json, true);
    }

    private function save_data($data) {
        return file_put_contents($this->data_file, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }

    public function index() {
        $this->load->view('users_view');
    }

    public function get_users() {
        echo json_encode($this->read_data());
    }

    public function update_user() {
        $userData = json_decode($this->input->raw_input_stream, true);
        $users = $this->read_data();

        foreach ($users as &$u) {
            if ($u['id'] == $userData['id']) {
                $u = array_merge($u, $userData);
                break;
            }
        }

        echo json_encode(['success' => $this->save_data($users)]);
    }

    public function delete_user() {
        $req = json_decode($this->input->raw_input_stream, true);
        $id = $req['id'];

        $users = array_filter($this->read_data(), function($u) use ($id) {
            return $u['id'] != $id;
        });

        echo json_encode(['success' => $this->save_data(array_values($users))]);
    }

    public function add_user() {
        $userData = json_decode($this->input->raw_input_stream, true);
        $users = $this->read_data();

        // Auto-generate new ID
        $newId = count($users) ? max(array_column($users, 'id')) + 1 : 1;
        $userData['id'] = $newId;
        $userData['joinedDate'] = date('Y-m-d');
        $userData['lastActive'] = date('Y-m-d');

        $users[] = $userData;

        echo json_encode(['success' => $this->save_data($users)]);
    }
}
