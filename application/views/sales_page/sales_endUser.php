<section class="user-section-main">
  <h1 class="page-title">End Users</h1>
  <p>Easily update user details, list of registered customer, and their contact information.</p>

  <div class="controls-container">
    <div class="search-bar">
      <input type="text" placeholder="Filter by name or category..." class="search-input">
      <button class="search-button">Search</button>
    </div>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th></th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Joined Date</th>
          <th>Last Active</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- JS will populate rows here -->
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <span>Showing 1-4 of 255 items</span>
    <div class="pagination-controls">
      <button class="page-btn prev">&lt;</button>
      <span class="page-number active">1</span>
      <span class="dots">...</span>
      <span class="page-number">17</span>
      <button class="page-btn next">&gt;</button>
    </div>
  </div>
</section>
</main>
</div>

<!-- Overlay & Popup -->
<div class="overlay" id="popupOverlay">
  <div class="popup">
    <div class="popup-header">
      <h2>View User</h2>
      <p>Use this section to view a user’s account information. Details are read-only.</p>
      <span class="close-btn" onclick="closePopup()">&times;</span>
    </div>
    <div class="popup-content">
      <h3>User Details</h3>
      <div class="view-details">
        <div class="detail-row">
          <label>First Name:</label>
          <span class="view-first-name">Juan</span>
        </div>
        <div class="detail-row">
          <label>Middle Initial (optional):</label>
          <span class="view-middle-initial">P.</span>
        </div>
        <div class="detail-row">
          <label>Surname:</label>
          <span class="view-surname">Santos</span>
        </div>
        <div class="detail-row">
          <label>Email Address:</label>
          <span class="view-email">juan.santos@gmail.com</span>
        </div>
        <div class="detail-row">
          <label>Phone Number:</label>
          <span class="view-phone">+639123456789</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/Glassify/assets/js/sales-end-user-popup.js"></script>
<script src="/Glassify/assets/js/admin-sidebar.js"></script>
<script src="/Glassify/assets/js/sales-end-user-pagination.js"></script>