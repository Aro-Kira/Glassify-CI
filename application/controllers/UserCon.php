<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserCon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library(['session', 'upload']);
        $this->load->helper(['url', 'form']);
    }

    // =============================
    // LOAD PROFILE PAGE
    // =============================
    public function profile()
    {
        $userID = $this->session->userdata('user_id'); // lowercase


        if (!$userID) {
            show_error('No active user session', 403);
            return;
        }

        $data['title'] = "Glassify - User Profile";
        $data['user'] = $this->User_model->get_by_id($userID);

        if (!$data['user']) {
            // fallback if user not found
            $data['user'] = (object) [
                'First_Name' => '',
                'Middle_Name' => '',
                'Last_Name' => '',
                'Email' => '',
                'PhoneNum' => '',
                'ImageUrl' => ''
            ];
        }

        $this->load->view('includes/header', $data);
        $this->load->view('user/profile', $data);
        $this->load->view('includes/footer');
    }

    // =============================
    // UPDATE PROFILE INFO
    // =============================
    public function update_profile()
    {
        $userID = $this->session->userdata('user_id'); // lowercase


        if (!$userID) {
            return $this->send_response('error', 'No active user session', 403);
        }

        // Validate POST inputs
        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->send_response('error', validation_errors(), 400);
        }

        $updateData = [
            'First_Name' => $this->input->post('firstname', TRUE),
            'Middle_Name' => $this->input->post('middlename', TRUE),
            'Last_Name' => $this->input->post('lastname', TRUE),
            'Email' => $this->input->post('email', TRUE),
            'PhoneNum' => $this->input->post('phone', TRUE),
        ];

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = $this->handle_upload('image', $userID, 'user_');
            if ($uploadResult['status'] === 'error') {
                return $this->send_response('error', $uploadResult['message'], 400);
            }
            $updateData['ImageUrl'] = $uploadResult['file_path'];
        }

        // Update user
        if (!$this->User_model->update_user($userID, $updateData)) {
            return $this->send_response('error', 'Failed to update profile', 500);
        }

        $this->send_response('success', 'Profile updated successfully');
    }

    // =============================
    // UPLOAD PROFILE PHOTO
    // =============================
    public function upload_photo()
    {
        $userID = $this->session->userdata('user_id'); // lowercase


        if (!$userID) {
            return $this->send_response('error', 'No active user session', 403);
        }

        $uploadResult = $this->handle_upload('photo', $userID, 'profile_');

        if ($uploadResult['status'] === 'error') {
            return $this->send_response('error', $uploadResult['message'], 400);
        }

        // Update user record
        if (!$this->User_model->update_user($userID, ['ImageUrl' => $uploadResult['file_path']])) {
            return $this->send_response('error', 'Failed to save profile photo', 500);
        }

        $this->send_response('success', 'Photo uploaded successfully', 200, [
            'image' => base_url($uploadResult['file_path'])
        ]);
    }

    // =============================
    // UPLOAD HANDLER
    // =============================
    private function handle_upload($field, $userID, $prefix)
    {
        // Upload configuration
        $config['upload_path'] = FCPATH . 'uploads/profile/'; // use FCPATH to avoid "path not valid"
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $prefix . $userID;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            return [
                'status' => 'error',
                'message' => $this->upload->display_errors('', '')
            ];
        }

        $fileData = $this->upload->data();
        $filePath = $fileData['full_path'];

        // -------------------------
        // Crop to 1:1 (square)
        // -------------------------
        $this->load->library('image_lib');
        list($width, $height) = getimagesize($filePath);
        $size = min($width, $height); // pick the smaller dimension
        $x = ($width - $size) / 2;
        $y = ($height - $size) / 2;

        $cropConfig = [
            'image_library' => 'gd2',
            'source_image' => $filePath,
            'maintain_ratio' => FALSE,
            'width' => $size,
            'height' => $size,
            'x_axis' => $x,
            'y_axis' => $y,
        ];

        $this->image_lib->initialize($cropConfig);

        if (!$this->image_lib->crop()) {
            return [
                'status' => 'error',
                'message' => $this->image_lib->display_errors('', '')
            ];
        }

        $this->image_lib->clear();

        return [
            'status' => 'success',
            'file_path' => 'uploads/profile/' . $fileData['file_name']
        ];
    }


    // =============================
    // JSON RESPONSE HELPER
    // =============================
    private function send_response($status, $message, $httpCode = 200, $extra = [])
    {
        $response = array_merge(['status' => $status, 'message' => $message], $extra);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($httpCode)
            ->set_output(json_encode($response));
    }
}
