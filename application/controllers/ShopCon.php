<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ShopCon extends CI_Controller
{

    public function products()
    {
        $data['title'] = "Glassify - Products";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/products', $data);
        $this->load->view('includes/footer');
    }


    public function product_2d()
    {
        $data['title'] = "Glassify - 2D Modeling";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/2DModeling', $data);
        $this->load->view('includes/footer');
    }


    public function addtocart()
    {
        $data['title'] = "Glassify - Add to Cart";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/addtocart', $data);
        $this->load->view('includes/footer');
    }

    public function checkout()
    {
        $data['title'] = "Glassify - Payment";
        $this->load->view('includes/header', $data);
        $this->load->view('shop/checkout', $data);
        $this->load->view('includes/footer');
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

}