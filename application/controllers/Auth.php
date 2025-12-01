<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
        $this->load->model('User_model');
    }

    // ===================== REGISTER PAGE =====================
    public function register()
    {
        $data['title'] = "Glassify - Register";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/register', $data);
        $this->load->view('includes/footer');
    }

    // ===================== PROCESS REGISTER =====================
    public function process_register()
    {
        // Form validation
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        $this->form_validation->set_rules('surname', 'Surname', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(base_url('register'));
        }

        $email = $this->input->post('email');

        // Check if email already exists
        if ($this->User_model->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email already registered.');
            redirect(base_url('register'));
        }

        // Save user data
        $data = [
            'First_Name' => $this->input->post('first_name'),
            'Middle_Name' => $this->input->post('middle_initial') ?: '',
            'Last_Name' => $this->input->post('surname'),
            'Email' => $email,
            'Password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'PhoneNum' => $this->input->post('phone'),
            'Role' => 'Customer', // default role
            'Status' => 'Active'
        ];

        $this->db->insert('users', $data);

        $this->session->set_flashdata('success', 'Registered successfully. Please log in.');
        redirect(base_url('login'));
    }

    // ===================== LOGIN PAGE =====================
    public function login()
    {
        $data['title'] = "Glassify - Login";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('includes/footer');
    }

    public function admin_login()
    {
        $data['title'] = "Glassify - Admin Login";
        $data['role_required'] = "Admin"; // Important
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_admin', $data);
        $this->load->view('includes/footer');
    }

    public function sales_login()
    {
        $data['title'] = "Glassify - Sales Representative Login";
        $data['role_required'] = "Sales Representative";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_sales', $data);
        $this->load->view('includes/footer');
    }

    public function inv_login()
    {
        $data['title'] = "Glassify - Inventory Login";
        $data['role_required'] = "Inventory Officer";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_inventory', $data);
        $this->load->view('includes/footer');
    }


    // ===================== PROCESS LOGIN =====================
   public function process_login()
{
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect($_SERVER['HTTP_REFERER']);
    }

    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $required_role = $this->input->post('required_role'); // from hidden input

    $user = $this->User_model->login($email);

    // Check user account
    if (!$user) {
        $this->session->set_flashdata('error', 'Email not found.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    // Check password
    if (!password_verify($password, $user->Password)) {
        $this->session->set_flashdata('error', 'Incorrect password.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    // Check if role matches the login page
    if ($required_role != $user->Role) {
        $this->session->set_flashdata('error', 'You are not allowed to log in on this page.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    // Set session
    $session_data = [
        'user_id' => $user->UserID,
        'email'   => $user->Email,
        'role'    => $user->Role,
        'logged_in' => TRUE
    ];
    $this->session->set_userdata($session_data);

    // Redirect based on role
    switch ($user->Role) {
        case 'Admin':
            redirect(base_url('admin-dashboard'));
            break;

        case 'Sales Representative':
            redirect(base_url('sales-dashboard'));
            break;

        case 'Inventory Officer':
            redirect(base_url('inventory-dashboard'));
            break;

        default:
            redirect(base_url('home-login'));
            break;
    }
}


    // ===================== LOGOUT =====================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('login'));
    }
}
