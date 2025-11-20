<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function home() {
        $data['title'] = "Glassify - Home";
        $this->load->view('includes/header', $data);
        $this->load->view('home', $data);
        $this->load->view('includes/footer');
    }

    public function home_login() {
        $data['title'] = "Glassify - Home";
        $this->load->view('includes/header', $data);
        $this->load->view('pages/home-login', $data);
        $this->load->view('includes/footer');
    }

 public function about() {
    $data['title'] = "Glassify - About Us";
    $this->load->view('includes/header', $data);
    $this->load->view('pages/about', $data); // this matches your file name
    $this->load->view('includes/footer');
}


/*     public function contact() {
        $data['title'] = "Glassify - Contact";
        $this->load->view('includes/header', $data);
        $this->load->view('contact', $data);
        $this->load->view('includes/footer');
    } */

  
   

    public function projects() {
        $data['title'] = "Glassify - Projects";
        $this->load->view('includes/header', $data);
        $this->load->view('pages/projects', $data);
        $this->load->view('includes/footer');
    }
    


  
}
