<link rel="stylesheet" href="<?php echo base_url('assets/css/general-customer/shop/addtocart_style.css'); ?>">

  <!-- Progress Navigation -->
  <div class="progress-nav">
    <div class="step active">Cart</div>
    <div class="divider"></div>
    <div class="step">Payment</div>
    <div class="divider"></div>
    <div class="step">Complete</div>
  </div>

  <main>

    <!-- Title outside sections -->
    <div class="cart-title">
      <h2>My Cart</h2>
      <div class="title-divider"></div>
    </div>

    <!-- Content row -->
    <div class="cart-container">
      <!-- Cart Section -->
      <section class="cart-section">
        <table class="cart-table">
          <thead>
            <tr>
              <th> </th>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody id="cart-body">
            <!-- Cart items injected here -->
          </tbody>
        </table>
        <button id="clear-cart" class="clear-btn">Clear Shopping Cart</button>
      </section>

      <!-- Order Summary Section -->
      <section class="order-summary">
        <div class="order-summary-content">
          <h3>Order Summary</h3>
          <p><span>Items:</span> <span id="summary-items">0</span></p>
          <p><span>Subtotal:</span> ₱<span id="summary-subtotal">0.00</span></p>
          <p><span>Shipping Fee:</span> ₱<span id="summary-shipping">0.00</span></p>
          <p><span>Handling Fee:</span> ₱<span id="summary-handling">0.00</span></p>
          <div class="summary-divider"></div>
          <p class="total"><span>Total:</span> ₱<span id="summary-total">0.00</span></p>
          <div class="btn-container">
            <a href="<?php echo base_url('payment'); ?>"> <!-- <--------- payment directory -->
              <button class="checkout-btn">Check Out</button>
            </a>
          </div>
        </div>
        <div class="quotation-content">
          <button class="generate-btn" id="openModal">Generate Quotation</button>
      </section>
    </div>

  </main>


  <script src="/Glassify/assets/js/cart.js"></script>
  <my-footer></my-footer>
  <div id="quotationModal" class="modal">
    <div class="modal-content">
      <span class="modal-close" id="closeModal">&times;</span>

      <div class="modal-header">Quotation</div>
      <p class="quotation-date">Date: 6/2/2025</p>

      <div class="section-title">Customer Information:</div>
      <div class="customer-info">
        <p><strong>Name:</strong> John Doe</p>
        <p><strong>Address:</strong> 123 Main Street, Anytown, USA</p>
        <p><strong>Email:</strong> john.doe@example.com</p>
        <p><strong>Phone:</strong> (123) 456-7890</p>
      </div>

      <div class="section-title">Quotation Details:</div>
      <table class="quotation-table">
        <thead>
          <tr>
            <th>Description</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Product A</td>
            <td>2</td>
            <td>$50.00</td>
            <td>$100.00</td>
          </tr>
          <tr>
            <td>Service B</td>
            <td>1</td>
            <td>$150.00</td>
            <td>$150.00</td>
          </tr>
          <tr>
            <td>Product C</td>
            <td>3</td>
            <td>$25.00</td>
            <td>$75.00</td>
          </tr>
        </tbody>
      </table>

      <div class="quotation-total">
        <p><strong>Subtotal:</strong> $325.00</p>
        <p><strong>Tax (10%):</strong> $32.50</p>
        <p><strong>Grand Total:</strong> $357.50</p>
      </div>
    </div>
  </div>




<script>
    const modal = document.getElementById("quotationModal");
    const openBtn = document.getElementById("openModal");
    const closeBtn = document.getElementById("closeModal");

    openBtn.onclick = () => modal.style.display = "block";
    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>