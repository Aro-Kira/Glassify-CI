
    <!-- ===== DASHBOARD PAGE ===== -->
    <section id="dashboard" class="page active">
        <h2>My Tasks</h2>
        <div class="tasks">
            <div class="card">
                <h2>16</h2>
                <p>Orders Assigned Today</p>
                <span>2 need Approval</span>
                <button onclick="window.location.href='sales_orders.html'">View Orders</button>
            </div>
            <div class="card">
                <h2>3</h2>
                <p>Receipts to Review</p>
                <span>Awaiting Verification</span>
                <button onclick="window.location.href='sales_payments.html'">Review Payments</button>
            </div>
            <div class="card warning">
                <h2><span class="circle-badge">3</span></h2>
                <p>High Priority Issues</p>
                <span>Customer support needed</span>
                <button onclick="window.location.href='sales_issues.html'">View Issues</button>
            </div>
        </div>

        <div class="dash-tabs">
            <h2>Recent Activities</h2>
        </div>

        <div class="activities-container">
            <div class="activities">
                <table>
                    <thead>
                        <tr class="activity-header">
                            <th>Action</th>
                            <th>Description</th>
                            <th>Role</th>
                            <th>User</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge info">Info</span></td>
                            <td>New order created (Order #1024)</td>
                            <td>Client</td>
                            <td>Client A</td>
                            <td>5/28/2025 – 09:45 AM</td>
                        </tr>
                        <tr>
                            <td><span class="badge success">Success</span></td>
                            <td>Quotation sent to Client B</td>
                            <td>Staff</td>
                            <td>M. Lopez</td>
                            <td>5/28/2025 – 08:30 AM</td>
                        </tr>
                        <tr>
                            <td><span class="badge error">Error</span></td>
                            <td>Inventory update failed (Glass Panel)</td>
                            <td>Admin</td>
                            <td>L. Doria</td>
                            <td>5/27/2025 – 05:12 PM</td>
                        </tr>
                        <tr>
                            <td><span class="badge warning">Warning</span></td>
                            <td>Stock running low: Aluminum Brackets</td>
                            <td>System</td>
                            <td>System</td>
                            <td>5/27/2025 – 02:15 PM</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</main>