<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmpCon extends CI_Controller {

    private $jsonFile = './application/data/emp.json'; // <- updated path

    public function index() {
        $data['title'] = "Employees";
        $this->load->helper('url'); // needed for base_url()
        $this->load->view('includes/header', $data);
        $this->load->view('employees/employees', $data);
        $this->load->view('includes/footer');
    }

    public function get_users() {
        header('Content-Type: application/json');
        if (file_exists($this->jsonFile)) {
            echo file_get_contents($this->jsonFile);
        } else {
            echo json_encode([]);
        }
    }

    public function save_users() {
        header('Content-Type: application/json');
        $input = json_decode($this->input->raw_input_stream, true);
        if ($input) {
            file_put_contents($this->jsonFile, json_encode($input, JSON_PRETTY_PRINT));
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
}
