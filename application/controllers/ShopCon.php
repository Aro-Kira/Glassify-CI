<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ShopCon extends CI_Controller
{


     public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

public function products()
{
    $data['title'] = "Glassify - Products";

    // Load products from database
    $data['products'] = $this->Product_model->get_products();

    $this->load->view('includes/header', $data);
    $this->load->view('shop/products', $data);  // now has $products available
    $this->load->view('includes/footer');
}



    public function product_2d()
    {
        $data['title'] = "Glassify - 2D Modeling";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/2DModeling', $data);
        $this->load->view('includes/footer');
    }




    public function checkout()
    {
        $data['title'] = "Glassify - Payment";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/checkout', $data);
        $this->load->view('includes/footer');
    }

    public function buy_now()
    {
        // Check if user is logged in
        if (!$this->session->userdata('user_id') && !$this->session->userdata('logged_in')) {
            redirect('auth/login?redirect=' . urlencode(current_url()));
            return;
        }

        // Get customization data from POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $customization_data = [
                'product_id'   => $this->input->post('product_id'),
                'shape'        => $this->input->post('shape'),
                'height'       => $this->input->post('height'),
                'width'        => $this->input->post('width'),
                'height_unit'  => $this->input->post('height_unit'),
                'width_unit'   => $this->input->post('width_unit'),
                'glass_type'   => $this->input->post('glass_type'),
                'thickness'    => $this->input->post('thickness'),
                'edge_work'    => $this->input->post('edge_work'),
                'frame_type'   => $this->input->post('frame_type'),
                'engraving'    => $this->input->post('engraving'),
                'price'        => $this->input->post('price'),
                'standard_size' => $this->input->post('standard_size'),
                'is_standard'  => $this->input->post('is_standard')
            ];

            // Store in session
            $this->session->set_userdata('temp_order_data', $customization_data);
        }

        // Redirect to checkout
        redirect('payment');
    }


    public function ewallet()
    {
        $data['title'] = "Glassify - Payment";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/ewallet', $data);
        $this->load->view('includes/footer');
    }
    public function complete()
    {
        $data['title'] = "Glassify - Order Complete";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/order_complete', $data);
        $this->load->view('includes/footer');
    }

    public function wishlist()
    {
        $data['title'] = "Glassify - Wishlist";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/wishlist', $data);
        $this->load->view('includes/footer');
    }

    public function order_tracking()
    {
        $data['title'] = "Glassify - Order Tracking";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/order_tracking', $data);
        $this->load->view('includes/footer');
    }

    public function terms_order()
    {
        $data['title'] = "Glassify - Terms of Ordering";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/terms_order', $data);
        $this->load->view('includes/footer');
    }

    public function waiting_order()
    {
        $data['title'] = "Glassify - Waiting for Order Approval";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/WaitingOrder', $data);
        $this->load->view('includes/footer');
    }

}