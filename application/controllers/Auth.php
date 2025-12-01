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

        if ($this->User_model->email_exists($email)) {
            $this->session->set_flashdata('error', 'Email already registered.');
            redirect(base_url('register'));
        }

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

        if ($this->User_model->register($data)) {
            $this->session->set_flashdata('success', 'Registration successful! You can now log in.');
            redirect(base_url('login'));
        } else {
            $this->session->set_flashdata('error', 'Registration failed. Please try again.');
            redirect(base_url('register'));
        }
    }

    // ===================== LOGIN PAGES =====================
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
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_admin', $data);
        $this->load->view('includes/footer');
    }

    public function sales_login()
    {
        $data['title'] = "Glassify - Sales Login";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_sales', $data);
        $this->load->view('includes/footer');
    }

    public function inv_login()
    {
        $data['title'] = "Glassify - Inventory Login";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_inventory', $data);
        $this->load->view('includes/footer');
    }

    // ===================== PROCESS ROLE LOGIN =====================
    public function process_role_login($role)
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->get_by_email($email);

        if ($user && password_verify($password, $user->Password)) {

            // Map URL-friendly role names to DB roles
            $role_map = [
                'Admin' => 'Admin',
                'Sales' => 'Sales Representative',
                'Inventory' => 'Inventory Officer',
                'Customer' => 'Customer'
            ];

            $db_role = $role_map[$role] ?? '';

            if ($user->Role !== $db_role) {
                $this->session->set_flashdata('error', "You are not authorized as $role.");
                redirect(base_url(strtolower($role) . '_login'));
            }

            // Set session
            $session_data = [
                'user_id' => $user->UserID,
                'user_name' => $user->First_Name . ' ' . $user->Last_Name,
                'user_role' => $user->Role,
                'is_logged_in' => true
            ];

            if ($user->Role === 'Customer') {
                $session_data['customer_id'] = $user->UserID;
            }

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
                case 'Customer':
                    redirect(base_url('home-login'));
                    break;
                default:
                    redirect(base_url());
            }

        } else {
            log_message('debug', 'Login attempt failed: email=' . $email . ', role=' . $role . ', DB role=' . ($user->Role ?? 'none'));
            $this->session->set_flashdata('error', 'Invalid email or password.');
            redirect(base_url(strtolower($role) . '_login'));
        }
    }

    // ===================== LOGOUT =====================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
