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
    $this->load->model('Product_model');

    // Get id from GET instead of method param
    $id = $this->input->get('id');

    if ($id) {
        $product = $this->Product_model->get_product($id);
    } else {
        // Get the latest product as default
        $product = $this->Product_model->get_products()[0] ?? null;
    }

    if (!$product) {
        show_404();
    }

    $data['title'] = "Glassify - 2D Modeling";
    $data['product'] = $product;

    $this->load->view('includes/header', $data);
    $this->load->view('shop/2DModeling', $data);
    $this->load->view('includes/footer');
}

// ShopCon.php
public function checkout()
{
    $userID = $this->session->userdata('user_id');
    $data['user'] = null;
    $data['addresses'] = ['Shipping' => null, 'Billing' => null];

    if ($userID) {
        $this->load->model('User_model');
        $data['user'] = $this->User_model->get_by_id($userID);
        $data['addresses'] = $this->User_model->get_addresses($userID);
    }

    // fallback if user not found
    if (!$data['user']) {
        $data['user'] = (object)[
            'First_Name' => '',
            'Middle_Name' => '',
            'Last_Name' => '',
            'Email' => '',
            'PhoneNum' => '',
            'ImageUrl' => ''
        ];
    }

    // fallback addresses
    foreach (['Shipping', 'Billing'] as $type) {
        if (!$data['addresses'][$type]) {
            $data['addresses'][$type] = (object)[
                'AddressLine' => '',
                'City' => '',
                'Province' => '',
                'Country' => '',
                'ZipCode' => '',
                'Note' => ''
            ];
        }
    }
        $data['title'] = "Glassify - Payment";
    $this->load->view('includes/header', $data);
    $this->load->view('shop/checkout', $data);
    $this->load->view('includes/footer');
}




    public function ewallet()
    {
        $data['title'] = "Glassify - Payment";
        
        // Get pending order data from session
        $data['pending_summary'] = $this->session->userdata('pending_order_summary');
        $data['pending_cart_ids'] = $this->session->userdata('pending_cart_ids');
        
        // Log session data for debugging
        log_message('debug', 'Ewallet Page - Session Data: ' . json_encode([
            'pending_summary' => $data['pending_summary'],
            'pending_cart_ids' => $data['pending_cart_ids'],
            'all_session_keys' => array_keys($this->session->all_userdata())
        ]));
        
        // Check if we have valid pending order data (with actual values)
        $has_valid_order = false;
        if (is_array($data['pending_summary']) && 
            isset($data['pending_summary']['total']) && 
            $data['pending_summary']['total'] > 0) {
            $has_valid_order = true;
        }
        
        log_message('debug', 'Ewallet Page - Has valid order: ' . ($has_valid_order ? 'YES' : 'NO'));
        
        // If no valid pending order, redirect back to checkout
        if (!$has_valid_order) {
            log_message('error', 'Ewallet Page - Redirecting: No valid pending order');
            $this->session->set_flashdata('error', 'No pending order found. Please place an order first.');
            redirect('payment');
            return;
        }
        
        $this->load->view('includes/header', $data);
        $this->load->view('shop/ewallet', $data);
        $this->load->view('includes/footer');
    }
    public function complete()
    {
        $data['title'] = "Glassify - Order Complete";
        
        // Get order ID from session
        $order_id = $this->session->userdata('last_order_id');
        $payment_method = $this->session->userdata('last_payment_method');
        
        // Load models
        $this->load->model('Order_model');
        $this->load->model('User_model');
        
        // Default values
        $data['order'] = null;
        $data['order_items'] = [];
        $data['summary'] = [
            'items' => 0,
            'subtotal' => 0,
            'shipping' => 0,
            'handling' => 0,
            'total' => 0
        ];
        $data['shipping_address'] = null;
        $data['payment_method'] = $payment_method ?? 'Cash on Delivery';
        
        if ($order_id) {
            // Get order with customer details
            $data['order'] = $this->Order_model->get_order_with_customer($order_id);
            
            // Get order items (customizations)
            $data['order_items'] = $this->Order_model->get_order_customizations($order_id);
            
            // Calculate summary
            $data['summary'] = $this->Order_model->calculate_order_summary($order_id);
            
            // Get customer shipping address
            $customer_id = $this->session->userdata('customer_id');
            if ($customer_id) {
                $addresses = $this->User_model->get_addresses($customer_id);
                $data['shipping_address'] = $addresses['Shipping'] ?? null;
                $data['billing_address'] = $addresses['Billing'] ?? null;
                $data['user'] = $this->User_model->get_by_id($customer_id);
            }
        }
        
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

        // Get order ID from URL parameter
        $order_id = $this->input->get('order');

        // Load models
        $this->load->model('Order_model');
        $this->load->model('User_model');

        // Default values
        $data['order'] = null;
        $data['order_items'] = [];
        $data['summary'] = [
            'items' => 0,
            'subtotal' => 0,
            'shipping' => 0,
            'handling' => 0,
            'total' => 0
        ];
        $data['payment'] = null;
        $data['progress'] = [];
        $data['shipping_address'] = null;
        $data['billing_address'] = null;

        if ($order_id) {
            // Get order tracking details
            $data['order'] = $this->Order_model->get_order_tracking_details($order_id);

            if ($data['order']) {
                // Get order items
                $data['order_items'] = $this->Order_model->get_order_customizations($order_id);

                // Calculate summary
                $data['summary'] = $this->Order_model->calculate_order_summary($order_id);

                // Get payment info
                $data['payment'] = $this->Order_model->get_order_payment($order_id);

                // Get progress steps based on status
                $data['progress'] = $this->Order_model->get_order_progress($data['order']->Status);

                // Get customer addresses
                $customer_id = $data['order']->Customer_ID;
                if ($customer_id) {
                    $addresses = $this->User_model->get_addresses($customer_id);
                    $data['shipping_address'] = $addresses['Shipping'] ?? null;
                    $data['billing_address'] = $addresses['Billing'] ?? null;
                }
            }
        }

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

    /**
     * Place Order - AJAX endpoint
     */
    public function place_order()
    {
        // Set JSON response header
        header('Content-Type: application/json');

        // Initialize log array for debugging
        $debug_log = [];
        $debug_log['timestamp'] = date('Y-m-d H:i:s');

        // Check if user is logged in
        $customer_id = $this->session->userdata('customer_id');
        $debug_log['customer_id'] = $customer_id;
        
        if (!$customer_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please log in to place an order.'
            ]);
            return;
        }

        // Get POST data
        $payment_method = $this->input->post('payment_method');
        $terms_accepted = $this->input->post('terms_accepted');
        $selected_cart_ids = $this->input->post('selected_cart_ids');
        
        $debug_log['payment_method'] = $payment_method;
        $debug_log['terms_accepted'] = $terms_accepted;
        $debug_log['selected_cart_ids'] = $selected_cart_ids;

        // Validate payment method
        if (empty($payment_method)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please select a payment method.'
            ]);
            return;
        }

        // Validate terms acceptance
        if ($terms_accepted !== 'true' && $terms_accepted !== '1') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please accept the Terms and Conditions.'
            ]);
            return;
        }

        // Load models
        $this->load->model('Cart_model');
        $this->load->model('Order_model');
        $this->load->model('User_model');

        // Get cart items
        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        $debug_log['cart_items_count_before_filter'] = count($cart_items);
        $debug_log['cart_items_raw'] = array_map(function($item) {
            return [
                'Cart_ID' => $item->Cart_ID ?? 'N/A',
                'Product_ID' => $item->Product_ID ?? 'N/A',
                'Quantity' => $item->Quantity ?? 0,
                'EstimatePrice' => $item->EstimatePrice ?? 'N/A',
                'Price' => $item->Price ?? 'N/A'
            ];
        }, $cart_items);
        
        // Filter to only selected items if IDs provided
        if (!empty($selected_cart_ids)) {
            $selected_ids = array_filter(array_map('intval', explode(',', $selected_cart_ids)));
            $debug_log['selected_ids_parsed'] = $selected_ids;
            
            if (!empty($selected_ids)) {
                $cart_items = array_filter($cart_items, function($item) use ($selected_ids) {
                    return in_array($item->Cart_ID, $selected_ids);
                });
                // Re-index array
                $cart_items = array_values($cart_items);
            }
        }
        
        $debug_log['cart_items_count_after_filter'] = count($cart_items);

        if (empty($cart_items)) {
            $debug_log['error'] = 'No items after filter';
            log_message('error', 'Place Order Debug: ' . json_encode($debug_log));
            
            echo json_encode([
                'status' => 'error',
                'message' => 'No items selected for checkout.',
                'debug' => $debug_log
            ]);
            return;
        }

        // Calculate totals for selected items only
        $subtotal = 0;
        $total_items = 0;
        foreach ($cart_items as $item) {
            $price = $item->EstimatePrice ?? $item->Price ?? 0;
            $debug_log['item_prices'][] = [
                'Cart_ID' => $item->Cart_ID,
                'EstimatePrice' => $item->EstimatePrice ?? 'null',
                'Price' => $item->Price ?? 'null',
                'used_price' => $price,
                'Quantity' => $item->Quantity
            ];
            $subtotal += $price * $item->Quantity;
            $total_items += $item->Quantity;
        }
        $shipping = $total_items * 25;
        $handling = $total_items * 10;
        $total_amount = $subtotal + $shipping + $handling;
        
        $debug_log['calculated_totals'] = [
            'subtotal' => $subtotal,
            'total_items' => $total_items,
            'shipping' => $shipping,
            'handling' => $handling,
            'total_amount' => $total_amount
        ];

        // Get shipping address
        $addresses = $this->User_model->get_addresses($customer_id);
        $shipping_address = '';
        if (isset($addresses['Shipping']) && $addresses['Shipping']) {
            $addr = $addresses['Shipping'];
            $shipping_address = implode(', ', array_filter([
                $addr->AddressLine,
                $addr->City,
                $addr->Province,
                $addr->Country,
                $addr->ZipCode
            ]));
        }

        // Get form data for shipping info update (optional)
        $firstname = $this->input->post('firstname');
        $lastname = $this->input->post('lastname');
        $address = $this->input->post('address');
        $city = $this->input->post('city');
        $province = $this->input->post('province');
        $country = $this->input->post('country');
        $zipcode = $this->input->post('zipcode');
        $note = $this->input->post('note');

        // Build delivery address from form if provided
        if (!empty($address)) {
            $shipping_address = implode(', ', array_filter([
                $address, $city, $province, $country, $zipcode
            ]));
        }

        // Get default sales rep
        $sales_rep_id = $this->Order_model->get_default_sales_rep();

        // Prepare order data
        $order_data = [
            'Customer_ID' => $customer_id,
            'SalesRep_ID' => $sales_rep_id,
            'TotalAmount' => $total_amount,
            'Status' => 'Pending',
            'PaymentStatus' => 'Pending',
            'DeliveryAddress' => $shipping_address,
            'SpecialInstructions' => $note
        ];

        // For E-Wallet: Store order data in session and redirect to payment page
        // Don't create order yet - wait for payment submission
        if ($payment_method === 'E-Wallet') {
            // Validate we have actual items with value
            if ($total_items <= 0 || $total_amount <= 0) {
                $debug_log['error'] = 'Invalid order amount';
                log_message('error', 'Place Order Debug: ' . json_encode($debug_log));
                
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid order amount. Please try again.',
                    'debug' => $debug_log
                ]);
                return;
            }

            // Prepare summary data
            $summary_data = [
                'items' => $total_items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'handling' => $handling,
                'total' => $total_amount
            ];
            
            $debug_log['summary_to_store'] = $summary_data;

            // Store pending order data in session (cart items remain intact)
            $this->session->set_userdata('pending_order_data', $order_data);
            $this->session->set_userdata('pending_cart_ids', $selected_cart_ids);
            $this->session->set_userdata('pending_order_summary', $summary_data);
            $this->session->set_userdata('last_payment_method', $payment_method);
            
            // Verify session was stored
            $stored_summary = $this->session->userdata('pending_order_summary');
            $debug_log['session_verification'] = [
                'stored_successfully' => !empty($stored_summary),
                'stored_data' => $stored_summary
            ];
            
            // Log to file
            log_message('debug', 'E-Wallet Order - Session stored: ' . json_encode($debug_log));

            echo json_encode([
                'status' => 'success',
                'message' => 'Redirecting to payment...',
                'redirect_url' => base_url('paying'),
                'debug' => $debug_log
            ]);
            return;
        }

        // For COD: Create order immediately
        $order_id = $this->Order_model->create_order($order_data);

        if (!$order_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create order. Please try again.'
            ]);
            return;
        }

        // Save order customizations from selected cart items only
        $this->Order_model->save_order_customizations($order_id, $cart_items);

        // Store order info in session for complete page
        $this->session->set_userdata([
            'last_order_id' => $order_id,
            'last_order_total' => $total_amount,
            'last_payment_method' => $payment_method
        ]);

        // Remove only the selected items from cart (not entire cart)
        if (!empty($selected_cart_ids)) {
            $selected_ids = array_filter(array_map('intval', explode(',', $selected_cart_ids)));
            foreach ($selected_ids as $cart_id) {
                $this->Cart_model->remove_item($cart_id);
            }
        } else {
            // If no selection specified, clear entire cart (fallback)
            $this->Cart_model->clear_cart($customer_id);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Order placed successfully!',
            'order_id' => $order_id,
            'redirect_url' => base_url('complete')
        ]);
    }

    /**
     * Submit E-Wallet Payment - Create order after payment receipt is uploaded
     */
    public function submit_ewallet_payment()
    {
        // Check if user is logged in
        $customer_id = $this->session->userdata('customer_id');
        if (!$customer_id) {
            $this->session->set_flashdata('error', 'Please log in to complete payment.');
            redirect('login');
            return;
        }

        // Get pending order data from session
        $order_data = $this->session->userdata('pending_order_data');
        $selected_cart_ids = $this->session->userdata('pending_cart_ids');

        if (empty($order_data)) {
            $this->session->set_flashdata('error', 'No pending order found. Please try again.');
            redirect('payment');
            return;
        }

        // Handle file upload
        $config['upload_path'] = './uploads/receipts/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'receipt_' . $customer_id . '_' . time();

        // Create upload directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('receipt')) {
            $this->session->set_flashdata('error', 'Failed to upload receipt: ' . $this->upload->display_errors('', ''));
            redirect('paying');
            return;
        }

        $upload_data = $this->upload->data();
        $receipt_path = 'uploads/receipts/' . $upload_data['file_name'];

        // Load models
        $this->load->model('Cart_model');
        $this->load->model('Order_model');

        // Now create the order
        $order_id = $this->Order_model->create_order($order_data);

        if (!$order_id) {
            $this->session->set_flashdata('error', 'Failed to create order. Please try again.');
            redirect('paying');
            return;
        }

        // Get cart items for order customizations
        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        
        // Filter to only selected items
        if (!empty($selected_cart_ids)) {
            $selected_ids = array_filter(array_map('intval', explode(',', $selected_cart_ids)));
            if (!empty($selected_ids)) {
                $cart_items = array_filter($cart_items, function($item) use ($selected_ids) {
                    return in_array($item->Cart_ID, $selected_ids);
                });
                $cart_items = array_values($cart_items);
            }
        }

        // Save order customizations
        $this->Order_model->save_order_customizations($order_id, $cart_items);

        // Save payment receipt reference
        $this->Order_model->save_payment_receipt($order_id, $receipt_path, $order_data['TotalAmount']);

        // Store order info in session for complete page
        $this->session->set_userdata([
            'last_order_id' => $order_id,
            'last_order_total' => $order_data['TotalAmount'],
            'last_payment_method' => 'E-Wallet'
        ]);

        // Remove selected items from cart
        if (!empty($selected_cart_ids)) {
            $selected_ids = array_filter(array_map('intval', explode(',', $selected_cart_ids)));
            foreach ($selected_ids as $cart_id) {
                $this->Cart_model->remove_item($cart_id);
            }
        }

        // Clear pending order data from session
        $this->session->unset_userdata(['pending_order_data', 'pending_cart_ids', 'pending_order_summary']);

        // Redirect to order complete page
        redirect('complete');
    }

    public function list_products()
    {
        $data['title'] = "Glassify - My Purchases";

        // Check if user is logged in
        $customer_id = $this->session->userdata('customer_id');
        
        if (!$customer_id) {
            // Redirect to login if not logged in
            redirect('login');
            return;
        }

        // Load Order model
        $this->load->model('Order_model');

        // Get customer's order items (purchases) from database
        $data['order_items'] = $this->Order_model->get_customer_order_items($customer_id);

        $this->load->view('includes/header', $data);
        $this->load->view('shop/list_product', $data);
        $this->load->view('includes/footer');
    }
    
}