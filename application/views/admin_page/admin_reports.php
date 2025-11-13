<section class="order-list-section">
  <div class="section-header">
    <h2>Reports</h2>

  </div>

  <div class="box">

    <div class="dash-tabs">
      <h2>Sales Reports</h2>
    </div>
    <div class="inventory-stats">
      <div class="stat-card">
        <p class="stat-value">₱12,450</p>
        <p class="stat-title">Total Sales</p>
      </div>
      <div class="stat-card">
        <p class="stat-value">320</p>
        <p class="stat-title">Orders</p>
      </div>
      <div class="stat-card">
        <p class="stat-value">₱38.91</p>
        <p class="stat-title">Avg. Order Value</p>
      </div>
      <div class="stat-card">
        <p class="stat-value">15</p>
        <p class="stat-title">Refunds</p>
      </div>
    </div>

    <!-- Sales Revenue -->
    <section class="sales-revenue">
      <div class="sales-header">
        <h3>Sales Revenue</h3>
        <div class="chart-legend">
          <span class="legend-weekly">Weekly Sales</span>
          <span class="legend-monthly">Monthly Sales</span>
        </div>
      </div>
      <div class="chart-container">
        <canvas id="salesChart"></canvas>
      </div>
    </section>

    <!-- Reports Charts -->
    <section class="reports-charts">
      <div class="chart-grid">

        <!-- Top-Selling Products -->
        <div class="chart-card">
          <h3>Top-Selling Products</h3>
          <canvas id="topProductsChart"></canvas>
        </div>

        <!-- Sales by Category -->
        <div class="chart-card">
          <h3>Sales by Category</h3>
          <canvas id="categoryChart"></canvas>
        </div>

        <!-- Sales by Payment Method -->
        <div class="chart-card">
          <h3>Sales by Payment Method</h3>
          <canvas id="paymentChart"></canvas>
        </div>

        <!-- Repeat vs New Customers -->
        <div class="chart-card">
          <h3>Repeat vs New Customers</h3>
          <canvas id="customersChart"></canvas>
        </div>

      </div>
    </section>


  </div>
</section>



</main>
</div>
<script src="/Glassify/assets/js/admin-sidebar.js"></script>
<script src="/Glassify/assets/js/dashboard-chart.js"></script>
<script src="/Glassify/assets/js/reports-chart.js"></script>

</body>

</html>