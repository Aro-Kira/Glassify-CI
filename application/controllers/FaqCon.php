<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FaqCon extends CI_Controller {

      /* 
======================================
=============FAQ Directory============
======================================
 */

 public function faq() {
        $data['title'] = "Glassify - FAQ";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq', $data);
        $this->load->view('includes/footer');
    }

    public function faq_ordering() {
        $data['title'] = "Glassify - FAQ Ordering & Product Customization";

        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_ordering', $data);
        $this->load->view('includes/footer');
      
    }
      public function faq_payment() {
        $data['title'] = "Glassify - FAQ Payments";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_payments', $data);
        $this->load->view('includes/footer');
    }
    public function faq_shipping() {
        $data['title'] = "Glassify - FAQ Shipping & Installation";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_shipping', $data);
        $this->load->view('includes/footer');
    }
    public function faq_warranty() {
        $data['title'] = "Glassify - FAQ Warranty";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_warranty', $data);
        $this->load->view('includes/footer');
    }
     public function faq_pricing() {
        $data['title'] = "Glassify - FAQ Pricing & Quotations";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_pricing', $data);
        $this->load->view('includes/footer');
    }
    public function faq_account() {
        $data['title'] = "Glassify - FAQ Account";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/faq_account', $data);
        $this->load->view('includes/footer');
    }

    public function faq_report() {
        $data['title'] = "Glassify - Report Issue";
        $this->load->view('includes/header', $data);
        $this->load->view('faq/report_issue', $data);
        $this->load->view('includes/footer');
    }









}
