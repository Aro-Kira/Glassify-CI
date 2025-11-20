<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    private $use_db = false;
    private $user_file;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form', 'file']);
        $this->user_file = APPPATH . 'data/users.json';
    }

    // ===================== LOGIN PAGE =====================
    public function login()
    {
        $data['title'] = "Glassify - Login";

        // Check for redirect after login
        $redirect = $this->input->get('redirect');
        if ($redirect) {
            $this->session->set_userdata('redirect_after_login', $redirect);
        }

        $this->load->view('includes/header', $data);
        $this->load->view('auth/login', $data);
        $this->load->view('includes/footer');
    }

    // ===================== ADMIN LOGIN PAGE =====================
    public function admin_login()
    {
        $data['title'] = "Glassify - Admin Login";

        $this->load->view('includes/header', $data);
        $this->load->view('auth/login_admin', $data);
        $this->load->view('includes/footer');
    }

    // ===================== REGISTER =====================
    public function register()
    {
        $data['title'] = "Glassify - Register";
        $this->load->view('includes/header', $data);
        $this->load->view('auth/register', $data);
        $this->load->view('includes/footer');
    }

    // ===================== PROCESS LOGIN =====================
    public function process_login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $is_admin = $this->input->post('is_admin');

        $user = $this->_get_user_by_email($email);

        if ($user && password_verify($password, $user['password'])) {

            // Handle admin login via customer form
            if (!$is_admin && $user['role'] === 'admin') {
                $user['role'] = 'customer';
            }

            // Prevent non-admin accessing admin login
            if ($is_admin && $user['role'] !== 'admin') {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                redirect(base_url('fl'));
                return;
            }

            // ✅ Set session
            $this->session->set_userdata([
                'user_id'      => $user['id'],
                'user_name'    => $user['name'],
                'user_role'    => $user['role'],
                'is_logged_in' => true
            ]);

            // ⭐ Redirect to page that triggered login
            $redirect_url = $this->session->userdata('redirect_after_login');
            if ($redirect_url) {
                $this->session->unset_userdata('redirect_after_login');
                redirect(base_url($redirect_url));
                return;
            }

            // Default redirect
            if ($user['role'] === 'admin' && $is_admin) {
                redirect(base_url('admin-dashboard'));
            } else {
                redirect(base_url('home-login'));
            }

        } else {
            $this->session->set_flashdata('error', 'Invalid email or password.');
            if ($is_admin) {
                redirect(base_url('fl'));
            } else {
                redirect(base_url('login'));
            }
        }
    }

    // ===================== PROCESS REGISTER =====================
    public function process_register()
    {
        $first_name     = $this->input->post('first_name');
        $middle_initial = $this->input->post('middle_initial');
        $surname        = $this->input->post('surname');
        $email          = $this->input->post('email');
        $password       = $this->input->post('password');
        $confirm_pass   = $this->input->post('confirm_password');
        $phone          = $this->input->post('phone');

        if ($password !== $confirm_pass) {
            $this->session->set_flashdata('error', 'Passwords do not match.');
            redirect(base_url('register'));
            return;
        }

        if ($this->_get_user_by_email($email)) {
            $this->session->set_flashdata('error', 'Email already registered.');
            redirect(base_url('register'));
            return;
        }

        $new_user = [
            'id'       => $this->_generate_user_id(),
            'name'     => trim("$first_name $middle_initial $surname"),
            'email'    => $email,
            'phone'    => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'customer',
        ];

        $this->_save_user($new_user);

        $this->session->set_flashdata('success', 'Registration successful! You can now log in.');
        redirect(base_url('login'));
    }

    // ===================== LOGOUT =====================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    // ===================== PRIVATE HELPERS =====================
    private function _get_user_by_email($email)
    {
        if ($this->use_db) {
            // For future database
        } else {
            $users = $this->_load_users_from_file();
            foreach ($users as $u) {
                if (strtolower($u['email']) === strtolower($email)) {
                    return $u;
                }
            }
        }
        return null;
    }

    private function _save_user($user_data)
    {
        if ($this->use_db) {
            // For future database
        } else {
            $users = $this->_load_users_from_file();
            $users[] = $user_data;
            write_file($this->user_file, json_encode($users, JSON_PRETTY_PRINT));
        }
    }

    private function _load_users_from_file()
    {
        if (!file_exists($this->user_file)) {
            return [];
        }
        $json = file_get_contents($this->user_file);
        return json_decode($json, true) ?: [];
    }

    private function _generate_user_id()
    {
        $users = $this->_load_users_from_file();
        return count($users) + 1;
    }
}
