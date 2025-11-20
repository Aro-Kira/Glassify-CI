<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartCon extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'cart']);
        $this->load->helper(['url']);
    }

    // ===================== ADD TO CART =====================
    public function add($product_id = null)
    {
        // Check: product id is required
        if ($product_id === null) {
            show_404();
            return;
        }

        // 🔒 If NOT logged in → redirect to login
        if (!$this->session->userdata('user_id')) {

            // Save page to return back after login
            $this->session->set_flashdata('redirect_back', current_url());

            redirect('login');
            return;
        }

        // TODO → Replace with real product data from DB
        $product_data = [
            'id'    => $product_id,
            'qty'   => 1,
            'price' => 100,           // temporary
            'name'  => 'Sample Item', // temporary
        ];

        $this->cart->insert($product_data);

        redirect('cart-page');
    }

    // ===================== SHOW CART PAGE =====================
    public function addtocart() // rename: SHOULD BE cart() or cart_page()
    {
        $data['title'] = "Glassify - MY CART";

        // Get cart contents
        $data['cart_items'] = $this->cart->contents();

        $this->load->view('includes/header', $data);
        $this->load->view('shop/addtocart', $data);
        $this->load->view('includes/footer');
    }

    // ===================== REMOVE ITEM =====================
    public function remove($rowid = null)
    {
        if ($rowid === null) {
            show_404();
            return;
        }

        $this->cart->remove($rowid);
        redirect('cart-page');
    }

    // ===================== CLEAR CART =====================
    public function clear()
    {
        $this->cart->destroy();
        redirect('cart-page');
    }
}
