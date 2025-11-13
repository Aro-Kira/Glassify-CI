<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderCon extends CI_Controller {

    public function get_json() {
        $file = APPPATH . 'data/orders.json'; // application/data/data.json
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $this->output
                 ->set_content_type('application/json')
                 ->set_output($json);
        } else {
            show_404();
        }
    }
}
