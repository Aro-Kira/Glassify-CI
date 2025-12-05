<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomizationCon extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Customization_model');
    }
public function save_customization() {
$customer_id = $this->session->userdata('customer_id');
    if (!$customer_id) {
        echo json_encode(['status'=>'error','message'=>'User not logged in']);
        log_message('error', 'SESSION DATA: ' . print_r($this->session->userdata(), true));

        return;
    }
    
    $data = [
        'Customer_ID'   => $customer_id,
        'Product_ID'    => $this->input->post('product_id'),
        'Dimensions'    => $this->input->post('dimensions') ?? '',
        'GlassShape'    => $this->input->post('shape') ?? '',
        'GlassType'     => $this->input->post('type') ?? '',
        'GlassThickness'=> $this->input->post('thickness') ?? '',
        'EdgeWork'      => $this->input->post('edge') ?? '',
        'FrameType'     => $this->input->post('frame') ?? '',
        'Engraving'     => $this->input->post('engraving') ?? '',
        'EstimatePrice' => $this->input->post('price') ?? 0,
        'DesignRef'     => $this->input->post('design_ref') ?? ''
    ];

    // Use insert() method (not add_customization which doesn't exist)
    $customization_id = $this->Customization_model->insert($data);

    echo json_encode(['status' => 'success', 'customization_id' => $customization_id]);
}

public function remove_customization() {
    $customization_id = $this->input->post('customization_id');

    if (!$customization_id) {
        echo json_encode(['status' => 'error', 'message' => 'No customization ID provided']);
        return;
    }

    // Use delete() method (refactored from delete_customization, now includes design image cleanup)
    $result = $this->Customization_model->delete($customization_id);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
}


}
