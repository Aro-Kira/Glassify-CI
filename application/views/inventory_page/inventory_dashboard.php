<section class="order-list-section">
  <div class="section-header">
    <h2>Dashboard</h2>
  </div>

  <div class="box">

        <div class="dash-tabs">
          <h2>My Tasks</h2>
        </div>
    <!-- Key Stats -->
    <section class="key-stats">
      <div class="stat-card stat-blue">
        <div class="stat-value">4</div>
        <div class="stat-title">Returned Items</div>
        <button class="review-btn" onclick="window.location.href='inventory_reports.html'">View Items</button>
      </div>
      <div class="stat-card stat-orange">
        <div class="stat-value">1</div>
        <div class="stat-title">Low Stock Alert</div>
        <button class="review-btn" onclick="window.location.href='inventory_inventory.html'">Review</button>
      </div>
      <div class="stat-card stat-green">
        <div class="stat-value">20</div>
        <div class="stat-title">Total Products</div>
        <button class="review-btn" onclick="window.location.href='inventory_products.html'">View Product</button>
      </div>
    </section>


            <div class="dash-tabs">
          <h2>Recent Activities</h2>
        </div>
    <!-- Recent Activities -->
    <section class="recent-activities">
      <table>
        <thead>
          <tr>
            <th>Action</th>
            <th>Item</th>
            <th>Change</th>
            <th>Description</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Threshold updated</td>
            <td>Tempered Glass 6mm</td>
            <td>Min: 15 → 25</td>
            <td>Increased due to demand</td>
            <td>5/28/2025 – 09:45 AM</td>
          </tr>
          <tr>
            <td>Stock reduced</td>
            <td>Mirror Tiles</td>
            <td>-5 sheets</td>
            <td>Damaged during handling</td>
            <td>5/28/2025 – 08:30 AM</td>
          </tr>
          <tr>
            <td>>Stock added</td>
            <td>Aluminum Frame</td>
            <td>+20 pieces</td>
            <td>New delivery from supplier</td>
            <td>5/27/2025 – 05:12 PM</td>
          </tr>
          <tr>
            <td>Item created</td>
            <td>Aluminum Frame</td>
            <td>50 pieces initial</td>
            <td>System</td>
            <td>5/27/2025 – 02:15 PM</td>
          </tr>
        </tbody>
      </table>
    </section>

  </div>
</section>

<script src="/Glassify/assets/js/dashboard-chart.js"></script>


