<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartCon extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Cart_model');
    }

    // ===================== ADD TO CART =====================
    public function add($product_id = null)
    {
        if ($product_id === null) {
            show_404();
            return;
        }

        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            $this->session->set_flashdata('redirect_back', current_url());
            redirect('login');
            return;
        }

        $data = [
            'Customer_ID' => $customer_id,
            'Product_ID' => $product_id,
            'CustomizationID' => null,
            'Quantity' => 1
        ];

        $this->Cart_model->add_to_cart($data);
        redirect('cart-page');
    }

    // ===================== ADD CUSTOMIZED ITEM =====================
    public function add_customized()
    {
        // 1️⃣ Get customer_id from session
        $customer_id = $this->session->userdata('customer_id'); // use customer_id everywhere
        if (!$customer_id) {
            $this->session->set_flashdata('error', 'Please log in to add items.');
            redirect('login');
            return;
        }

        // 2️⃣ Get POST data
        $post = $this->input->post();

        // 3️⃣ Prepare customization data
        $custom_data = [
            'Customer_ID' => $customer_id,
            'Product_ID' => $post['product_id'] ?? null,
            'Dimensions' => $post['dimensions'] ?? null,
            'GlassShape' => $post['shape'] ?? null,
            'GlassType' => $post['type'] ?? null,
            'GlassThickness' => $post['thickness'] ?? null,
            'EdgeWork' => $post['edge'] ?? null,
            'FrameType' => $post['frame'] ?? null,
            'Engraving' => $post['engraving'] ?? null,
            'DesignRef' => $post['design_ref'] ?? null,
            'EstimatePrice' => $post['price'] ?? 0
        ];

        // 4️⃣ Save customization
        $customization_id = $this->Cart_model->save_customization($custom_data);

        if (!$customization_id) {
            $this->session->set_flashdata('error', 'Failed to save customization.');
            redirect('product/' . $post['product_id']);
            return;
        }

        // 5️⃣ Prepare cart data
        $cart_data = [
            'Customer_ID' => $customer_id,
            'Product_ID' => $post['product_id'] ?? null,
            'CustomizationID' => $customization_id,
            'Quantity' => $post['quantity'] ?? 1
        ];

        // 6️⃣ Add to cart
        $this->Cart_model->add_to_cart($cart_data);

        // 7️⃣ Redirect to cart page
        $this->session->set_flashdata('success', 'Customized item added to cart!');
        redirect('cart-page');
    }

    public function add_customized_ajax()
    {
        $customer_id = $this->session->userdata('customer_id');
        if (!$customer_id) {
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }

        $post = $this->input->post();
        $design_ref = null;

        // 1️⃣ Handle design image upload (base64 to file)
        if (!empty($post['design_image'])) {
            $design_ref = $this->save_design_image($post['design_image'], $customer_id);
        }

        // 2️⃣ Prepare customization data
        $custom_data = [
            'Customer_ID' => $customer_id,
            'Product_ID' => $post['product_id'] ?? null,
            'Dimensions' => $post['dimensions'] ?? null,
            'GlassShape' => $post['shape'] ?? null,
            'GlassType' => $post['type'] ?? null,
            'GlassThickness' => $post['thickness'] ?? null,
            'EdgeWork' => $post['edge'] ?? null,
            'FrameType' => $post['frame'] ?? null,
            'Engraving' => $post['engraving'] ?? null,
            'DesignRef' => $design_ref,
            'EstimatePrice' => $this->clean_price($post['price'] ?? 0),
            'PriceBreakdown' => $post['price_breakdown'] ?? null
        ];

        // 3️⃣ Save customization
        $this->load->model('Cart_model');
        $customization_id = $this->Cart_model->save_customization($custom_data);

        if (!$customization_id) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save customization']);
            return;
        }

        // 4️⃣ Add to cart
        $cart_data = [
            'Customer_ID' => $customer_id,
            'Product_ID' => $post['product_id'] ?? null,
            'CustomizationID' => $customization_id,
            'Quantity' => $post['quantity'] ?? 1
        ];

        $cart_id = $this->Cart_model->add_to_cart($cart_data);

        // 5️⃣ Return updated cart info
        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        $cart_count = count($cart_items);

        echo json_encode([
            'status' => 'success',
            'message' => 'Customized item added to cart',
            'customization_id' => $customization_id,
            'cart_id' => $cart_id,
            'cart_count' => $cart_count,
            'design_ref' => $design_ref
        ]);
    }

    /**
     * Save base64 design image to file
     * Preserves the original image format (png, jpeg, gif, webp) from the data URL
     */
    private function save_design_image($base64_data, $customer_id)
    {
        // Create designs directory if it doesn't exist
        $upload_dir = FCPATH . 'uploads/designs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Extract image format from data URL (default to png if not found)
        $extension = 'png';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_data, $matches)) {
            $format = strtolower($matches[1]);
            // Map common formats to file extensions
            $format_map = [
                'jpeg' => 'jpg',
                'jpg' => 'jpg',
                'png' => 'png',
                'gif' => 'gif',
                'webp' => 'webp',
                'svg+xml' => 'svg'
            ];
            $extension = isset($format_map[$format]) ? $format_map[$format] : 'png';
        }

        // Remove data URL prefix if present
        if (strpos($base64_data, 'data:image') === 0) {
            $base64_data = preg_replace('/^data:image\/[\w+]+;base64,/', '', $base64_data);
        }

        // Decode base64 data
        $image_data = base64_decode($base64_data);
        if ($image_data === false) {
            return null;
        }

        // Generate unique filename with correct extension
        $filename = 'design_' . $customer_id . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $upload_dir . $filename;

        // Save image file
        if (file_put_contents($filepath, $image_data)) {
            return 'uploads/designs/' . $filename;
        }

        return null;
    }

    /**
     * Clean price string to decimal
     */
    private function clean_price($price)
    {
        if (is_numeric($price)) {
            return floatval($price);
        }
        // Remove currency symbols and commas
        $cleaned = preg_replace('/[^0-9.]/', '', $price);
        return floatval($cleaned);
    }




    // ===================== SHOW CART PAGE =====================
    public function cart_page()
    {
        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            redirect('login');
            return;
        }

        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        
        // Fetch user data for quotation (customer table only has Customer_ID and UserID,
        // but we need First_Name, Email, PhoneNum, Address from the user table)
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');
        $customer = $this->User_model->get_by_id($user_id);
        
        // Fallback if user not found
        if (!$customer) {
            $customer = (object)[
                'First_Name' => '',
                'Middle_Name' => '',
                'Last_Name' => '',
                'Email' => '',
                'PhoneNum' => '',
                'Address' => ''
            ];
        }

        $data['title'] = "Glassify - MY CART";
        $data['cart_items'] = $cart_items;
        $data['customer'] = $customer;
        $data['summary'] = $this->calculate_summary($cart_items);

        $this->load->view('includes/header', $data);
        $this->load->view('shop/addtocart', $data);
        $this->load->view('includes/footer');
    }

    // ===================== REMOVE ITEM =====================
    public function remove($cart_id = null)
    {
        if ($cart_id === null) {
            show_404();
            return;
        }

        $this->Cart_model->remove_item($cart_id);
        redirect('cart-page');
    }

    // ===================== CLEAR CART =====================
    public function clear()
    {
        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            redirect('login');
            return;
        }

        $this->Cart_model->clear_cart($customer_id);
        redirect('cart-page');
    }

    // ===================== UPDATE QUANTITY =====================
    public function update_qty()
    {
        $cart_id = $this->input->post('cart_id');
        $qty = (int) $this->input->post('quantity');

        if (!$cart_id || !$qty) {
            echo json_encode(['status' => 'error']);
            return;
        }

        $this->db->where('Cart_ID', $cart_id);
        $this->db->update('cart', ['Quantity' => $qty]);
        echo json_encode(['status' => 'success']);
    }

    public function update_qty_ajax()
    {
        $cart_id = $this->input->post('cart_id');
        $qty = (int) $this->input->post('quantity');
        $customer_id = $this->session->userdata('customer_id');


        if (!$cart_id || !$customer_id) {
            echo json_encode(['status' => 'error']);
            return;
        }

        $this->db->where('Cart_ID', $cart_id);
        $this->db->update('cart', ['Quantity' => $qty]);

        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        $summary = $this->calculate_summary($cart_items);

        echo json_encode(['status' => 'success', 'summary' => $summary]);
    }

    // ===================== REMOVE ITEM AJAX =====================
   public function remove_ajax()
{
    $cart_id = $this->input->post('cart_id');
    $customer_id = $this->session->userdata('customer_id');

    if (!$cart_id || !$customer_id) {
        echo json_encode(['status' => 'error']);
        return;
    }

    // 1. Get cart row first (to retrieve CustomizationID)
    $cart_item = $this->db->where('Cart_ID', $cart_id)->get('cart')->row();

    if (!$cart_item) {
        echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
        return;
    }

    // 2. Remove the cart item
    $this->Cart_model->remove_item($cart_id);

    // 3. Remove customization if exists
    if (!empty($cart_item->CustomizationID)) {
        $this->load->model('Customization_model');
        $this->Customization_model->delete($cart_item->CustomizationID);
    }

    // 4. Refresh updated cart list
    $cart_items = $this->Cart_model->get_cart_items($customer_id);
    $summary = $this->calculate_summary($cart_items);

    echo json_encode([
        'status'  => 'success',
        'summary' => $summary
    ]);
}


    // ===================== CLEAR CART AJAX =====================
   public function clear_ajax()
{
    $customer_id = $this->session->userdata('customer_id');

    if (!$customer_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        return;
    }

    // 1. Get all cart items for the user (to extract CustomizationID)
    $cart_items = $this->db
        ->where('Customer_ID', $customer_id)
        ->get('cart')
        ->result();

    // 2. Collect customization IDs
    $customization_ids = [];
    foreach ($cart_items as $item) {
        if (!empty($item->CustomizationID)) {
            $customization_ids[] = $item->CustomizationID;
        }
    }

    // 3. Delete all cart items
    $this->db->where('Customer_ID', $customer_id)->delete('cart');

    // 4. Delete all customization entries
    if (!empty($customization_ids)) {
        $this->load->model('Customization_model');
        $this->Customization_model->delete_multiple($customization_ids);
    }

    echo json_encode([
        'status'  => 'success',
        'summary' => [
            'items' => 0,
            'subtotal' => 0,
            'shipping' => 0,
            'handling' => 0,
            'total' => 0
        ]
    ]);
}


    // ===================== GET CART DATA FOR QUOTATION =====================
    public function get_cart_ajax()
    {
        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            echo json_encode(['status' => 'error']);
            return;
        }

        $cart_items = $this->Cart_model->get_cart_items($customer_id);
        $summary = $this->calculate_summary($cart_items);

        $items = [];
        foreach ($cart_items as $item) {
            $price = $item->Price ?? 0;
            
            // Build customization details string
            $customization = '';
            if (!empty($item->CustomizationID)) {
                $details = [];
                if (!empty($item->Dimensions)) $details[] = "Size: {$item->Dimensions}";
                if (!empty($item->GlassShape)) $details[] = "Shape: " . ucfirst($item->GlassShape);
                if (!empty($item->GlassType)) $details[] = "Type: " . ucfirst($item->GlassType);
                if (!empty($item->GlassThickness)) $details[] = "Thickness: {$item->GlassThickness}";
                if (!empty($item->EdgeWork)) $details[] = "Edge: " . ucfirst(str_replace('-', ' ', $item->EdgeWork));
                if (!empty($item->FrameType)) $details[] = "Frame: " . ucfirst($item->FrameType);
                $customization = implode(' | ', $details);
            }
            
            $items[] = [
                'cart_id' => $item->Cart_ID,
                'description' => $item->ProductName,
                'quantity' => $item->Quantity,
                'unit_price' => $price,
                'total' => $price * $item->Quantity,
                'customization' => $customization,
                'image' => !empty($item->ImageUrl) ? base_url('uploads/products/' . $item->ImageUrl) : null,
                'design_ref' => !empty($item->DesignRef) ? base_url($item->DesignRef) : null,
                'has_design' => !empty($item->DesignRef)
            ];
        }

        echo json_encode(['status' => 'success', 'items' => $items, 'summary' => $summary]);

    }

    // ===================== GET SELECTED CART ITEMS =====================
    public function get_selected_cart_ajax()
    {
        $customer_id = $this->session->userdata('customer_id');

        if (!$customer_id) {
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
            return;
        }

        // Get selected cart IDs from POST or GET
        $selected_ids = $this->input->get('selected');
        if (empty($selected_ids)) {
            $selected_ids = $this->input->post('selected');
        }

        // Convert comma-separated string to array
        if (is_string($selected_ids)) {
            $selected_ids = array_filter(array_map('intval', explode(',', $selected_ids)));
        }

        // Get all cart items
        $cart_items = $this->Cart_model->get_cart_items($customer_id);

        // Filter to only selected items if IDs provided
        if (!empty($selected_ids)) {
            $cart_items = array_filter($cart_items, function($item) use ($selected_ids) {
                return in_array($item->Cart_ID, $selected_ids);
            });
        }

        $summary = $this->calculate_summary($cart_items);

        $items = [];
        foreach ($cart_items as $item) {
            $price = $item->Price ?? 0;
            
            // Build customization details string
            $customization = '';
            if (!empty($item->CustomizationID)) {
                $details = [];
                if (!empty($item->Dimensions)) $details[] = "Size: {$item->Dimensions}";
                if (!empty($item->GlassShape)) $details[] = "Shape: " . ucfirst($item->GlassShape);
                if (!empty($item->GlassType)) $details[] = "Type: " . ucfirst($item->GlassType);
                if (!empty($item->GlassThickness)) $details[] = "Thickness: {$item->GlassThickness}";
                if (!empty($item->EdgeWork)) $details[] = "Edge: " . ucfirst(str_replace('-', ' ', $item->EdgeWork));
                if (!empty($item->FrameType)) $details[] = "Frame: " . ucfirst($item->FrameType);
                $customization = implode(' | ', $details);
            }
            
            $items[] = [
                'cart_id' => $item->Cart_ID,
                'description' => $item->ProductName,
                'quantity' => $item->Quantity,
                'unit_price' => $price,
                'total' => $price * $item->Quantity,
                'customization' => $customization,
                'image' => !empty($item->ImageUrl) ? base_url('uploads/products/' . $item->ImageUrl) : null,
                'design_ref' => !empty($item->DesignRef) ? base_url($item->DesignRef) : null,
                'has_design' => !empty($item->DesignRef)
            ];
        }

        echo json_encode([
            'status' => 'success', 
            'items' => $items, 
            'summary' => $summary,
            'selected_ids' => $selected_ids
        ]);
    }

    // ===================== HELPER =====================
    private function calculate_summary($cart_items)
    {
        $subtotal = 0;
        $total_items = 0;



        foreach ($cart_items as $item) {
            $price = $item->EstimatePrice ?? 100;
            $subtotal += $price * $item->Quantity;
            $total_items += $item->Quantity;
 
        }
 
      ;

        $shipping =  $total_items * 25;
        $handling =  $total_items * 10;
        $total = $subtotal + $shipping + $handling;
        


        return [
            'items' => $total_items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'handling' => $handling,
            'total' => $total
        ];
    }

}
