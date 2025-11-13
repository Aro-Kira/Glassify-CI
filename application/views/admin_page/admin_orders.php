<script>
  // Pass the URL from PHP to JS
  const ordersJsonUrl = "<?php echo base_url('OrderCon/get_json'); ?>";
</script>

<script src="<?php echo base_url('assets/js/admin-js/order.js'); ?>"></script>





<!-- Orders -->
<section class="order-list-section">
  <div class="section-header">
    <h2>Order <span class="found-text">16 Orders found</span></h2>

  </div>
  <div class="order-tabs">
    <div class="tab-buttons">
      <button class="tab-button active">All orders</button>
      <button class="tab-button">Completed</button>
      <button class="tab-button">Pending</button>
      <button class="tab-button">Cancel</button>
       <div class="search-container">
        <input type="text" placeholder="Search products">
        <button class="search-button">Search</button>
      </div>
    </div>

    <div class="tab-right">
     
      <div class="custom-calendar">
    <button id="calendar-btn">
        <span id="month">May</span>
        <span id="year">2025</span>
    </button>

    <div class="calendar-dropdown" id="calendar-dropdown">
        <!-- Month Selector -->
        <div class="month-selector">
            <button onclick="prevMonth()">&#8592;</button>
            <span id="dropdown-month">May</span>
            <button onclick="nextMonth()">&#8594;</button>
        </div>

        <!-- Year Selector -->
        <div class="year-selector">
            <button onclick="prevYear()">&#8592;</button>
            <span id="dropdown-year">2025</span>
            <button onclick="nextYear()">&#8594;</button>
        </div>

        <!-- Show All Button -->
        <div class="calendar-reset">
            <button id="reset-calendar">Show All</button>
        </div>
    </div>
</div>

    </div>
  </div>


  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Order ID</th>
          <th>Product Name</th>
          <th>Address</th>
          <th>Date</th>
          <th>Price</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>#G1001</td>
          <td>Tempered Glass</td>
          <td>351 Shearwood...</td>
          <td>20/03/2025</td>
          <td>₱376.00</td>
          <td><span class="status-badge completed">Confirmed</span></td>
          <td class="action-cell">⋮</td>
        </tr>
        <tr>
          <td>2</td>
          <td>#G1002</td>
          <td>Aluminum Frame</td>
          <td>6391 Elgin St. C...</td>
          <td>21/03/2025</td>
          <td>₱276.00</td>
          <td><span class="status-badge pending">Pending</span></td>
          <td class="action-cell">⋮</td>
        </tr>
        <tr>
          <td>3</td>
          <td>#G1003</td>
          <td>Sliding Track</td>
          <td>8502 Preston...</td>
          <td>01/05/2025</td>
          <td>₱300.00</td>
          <td><span class="status-badge canceled">Canceled</span></td>
          <td class="action-cell">⋮</td>
        </tr>
        <tr>
          <td>4</td>
          <td>#G1004</td>
          <td>Handle Set</td>
          <td>4517 Washington...</td>
          <td>02/04/2025</td>
          <td>₱200.00</td>
          <td><span class="status-badge pending">Pending</span></td>
          <td class="action-cell">⋮</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Showing 1-10 of 255 items</span>
    <div class="pagination-controls">
      <button><i class="fas fa-chevron-left"></i></button>
      <button class="active">1</button>
      <button><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
  <div id="actionMenu" class="action-menu hidden">
    <ul>
      <li><a href="#">View</a></li>
      <li><a href="#">Complete</a></li>
      <li><a href="#">Cancel</a></li>
      <li><a href="#">Delete</a></li>
    </ul>
  </div>


</section>


<!-- Order Schedule -->
<section class="order-schedule-section">
  <div class="table-container">
    <div class="section-header-schedule">
      <h2>Order Schedule Approval <img src="<?php echo base_url('assets/images/img_admin/approved.svg'); ?>"
          class="approve-img"></h2>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Order ID</th>
          <th>Scheduled Date</th>
          <th>Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>#G1002</td>
          <td>21/03/2025</td>
          <td>₱276.00</td>
          <td><button class="btn-review">Review</button></td>
        </tr>
        <tr>
          <td>2</td>
          <td>#G1005</td>
          <td>27/03/2025</td>
          <td>₱5,500.00</td>
          <td><button class="btn-review">Review</button></td>
        </tr>
        <tr>
          <td>3</td>
          <td>#G1007</td>
          <td>01/04/2025</td>
          <td>₱1,200.00</td>
          <td><button class="btn-review">Review</button></td>
        </tr>
        <tr>
          <td>4</td>
          <td>#G1008</td>
          <td>01/04/2025</td>
          <td>₱430.00</td>
          <td><button class="btn-review">Review</button></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Showing 1-4 of 255 items</span>
    <div class="pagination-controls">
      <button><i class="fas fa-chevron-left"></i></button>
      <button class="active">1</button>
      <button><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>
</main>


<!-- Popup Overlay -->
<div class="popup-overlay" id="orderPopup">
  <div class="popup">
    <span class="close-btn" id="closePopup">&times;</span>
    <h3>Order Details</h3>

    <table class="order-details">
      <tbody>
        <tr>
          <td>Order ID:</td>
          <td>#GI001</td>
        </tr>
        <tr>
          <td>Product:</td>
          <td>Tempered Glass Panel</td>
        </tr>
        <tr>
          <td>Address:</td>
          <td>123 Glass St. Manila</td>
        </tr>
        <tr>
          <td>Date:</td>
          <td>30/05/2025</td>
        </tr>
        <tr>
          <td>Status:</td>
          <td>Pending</td>
        </tr>
        <tr>
          <td>Shape:</td>
          <td>Rectangle</td>
        </tr>
        <tr>
          <td>Dimension:</td>
          <td>24", 0", 18", 0"</td>
        </tr>
        <tr>
          <td>Type:</td>
          <td>Tempered</td>
        </tr>
        <tr>
          <td>Thickness:</td>
          <td>8mm</td>
        </tr>
        <tr>
          <td>Edge Work:</td>
          <td>Flat Polish</td>
        </tr>
        <tr>
          <td>Engraving:</td>
          <td>N/A</td>
        </tr>
        <tr>
          <td>File Attached:</td>
          <td><a href="#">design.pdf</a></td>
        </tr>
        <tr>
          <td>Total Quotation (₱):</td>
          <td>3,100</td>
        </tr>
      </tbody>
    </table>

    <div class="barcode">
      <img src="https://barcode.tec-it.com/barcode.ashx?data=GI001&code=Code128&translate-esc=false" alt="Barcode">
    </div>

    <button class="export-btn">Export</button>
    <button class="approve-btn">Approve Order</button>

  </div>
</div>

