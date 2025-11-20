<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

defined('BASEPATH') OR exit('No direct script access allowed');

class ProductCon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->helper(array('url', 'form'));
    }


    // ---------------- ADD PRODUCT ----------------
    public function add_product()
{
    $this->load->library('upload');

    // Create folder if it doesn't exist
    $upload_path = './uploads/products/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['encrypt_name']  = TRUE;
    $this->upload->initialize($config);

    $image = null;
    if (!empty($_FILES['productImage']['name'])) {
        if ($this->upload->do_upload('productImage')) {
            $image = $this->upload->data('file_name');
        } else {
            echo json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]);
            return;
        }
    }

    $data = [
        'ProductName' => $this->input->post('name', true),
        'Category'    => $this->input->post('category', true),
        'Material'    => $this->input->post('material', true),
        'Price'       => $this->input->post('price', true),
        'ImageUrl'    => $image,
        'DateAdded'   => date('Y-m-d H:i:s'),
        'Status'      => 'active' // default value
    ];

    if ($this->Product_model->insert_product($data)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}




// ---------------- UPDATE PRODUCT ----------------
public function update_product($id)
{
    $this->load->library('upload');

    $config['upload_path'] = './uploads/products/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['encrypt_name'] = TRUE;
    $this->upload->initialize($config);

    // Collect all editable fields
    $data = [
        'ProductName' => $this->input->post('name', true),
        'Price'       => $this->input->post('price', true),
        'Category'    => $this->input->post('category', true),
        'Material'    => $this->input->post('material', true)
    ];

    // Handle image upload if provided
    if (!empty($_FILES['productImage']['name'])) {
        if ($this->upload->do_upload('productImage')) {
            $data['ImageUrl'] = $this->upload->data('file_name');
        } else {
            echo json_encode(['status' => 'error', 'msg' => $this->upload->display_errors()]);
            return;
        }
    }

    if ($this->Product_model->update_product($id, $data)) {
        echo json_encode(['status' => 'updated']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}


    // ---------------- DELETE PRODUCT ----------------
    public function delete_product($id)
    {
        if ($this->Product_model->delete_product($id)) {
            echo json_encode(['status' => 'deleted']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
