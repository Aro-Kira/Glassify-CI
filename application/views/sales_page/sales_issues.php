<section class="user-section-main">
    <h1 class="page-title">Issue/Support</h1>
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
                    <th>Ticket ID</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Email</th>
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

<div class="popup-overlay" id="popupOverlay">
    <div class="popup ticket-popup">
        <div class="popup-header">
            <h2>Customer Contact Info</h2>
            <span class="close-btn" id="closePopupTicket">&times;</span>
        </div>

        <div class="customer-contact-info view-details">

            <div class="detail-row">
                <label>Name:</label>
                <span class="contact-name"></span>
            </div>
            <div class="detail-row">
                <label>Email:</label>
                <span class="contact-email"></span>
            </div>
            <div class="detail-row">
                <label>Phone:</label>
                <span class="contact-phone"></span>
            </div>
            <div class="detail-row">
                <label>Order ID:</label>
                <span class="contact-order-id"></span>
            </div>

        </div>

        <div class="issue-section">
            <h3 class="issue-section-title">Issue Category</h3>
            <div class="category-field">
                <input type="text" value="General Inquiry" readonly class="category-input">
            </div>
        </div>

        <div class="priority-section">
            <h3 class="issue-section-title">Priority:</h3>
            <div class="priority-tag">
                <span class="priority-dot"></span>
            </div>
        </div>

        <div class="description-section">
            <h3 class="issue-section-title">Description</h3>
            <div class="description-section-text">
                <textarea readonly class="description-textarea"></textarea>
            </div>
        </div>

        <div class="popup-actions ticket-actions">
            <button class="submit-btn resolved-btn">Mark as Resolved</button>
            <button class="cancel-btn">Cancel</button>
        </div>
    </div>
</div>

<script src="/Glassify/assets/js/admin-sidebar.js"></script>
<script src="/Glassify/assets/js/sales-issues-pagination.js"></script>