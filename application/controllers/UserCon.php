<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserCon extends CI_Controller {

    public function profile() {
        $data['title'] = "Glassify - User Profile";
        // Load full page with header, profile view, and footer
        $this->load->view('includes/header', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('includes/footer');
    }

}
