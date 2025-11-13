<!-- Appointment Section -->
<section class="appointment-section-main">
    <h1 class="page-title">Appointment</h1>

    <!-- Filters -->
    <div class="controls-container">
        <input type="date" class="filter-date">
        <select class="filter-status">
            <option>All Statuses</option>
            <option>Order Placed</option>
            <option>Ocular Visit</option>
            <option>In Fabrication</option>
            <option>Installed</option>
            <option>Completed</option>
        </select>
        <input type="text" placeholder="Search by client name..." class="filter-search">
        <button class="apply-btn">Apply</button>
        <button class="add-appointment-button">+ Add new</button>
    </div>

    <!-- Progress Steps -->
<div class="progress-steps">
    <div class="step">
        <img src="<?php echo base_url('assets/images/img_admin/checkout.png'); ?>" alt="Order Placed">
        <p>Order Placed</p>
        <span class="square blue"></span>
    </div>
    <img src="<?php echo base_url('assets/images/img_admin/double-arrow.svg'); ?>" alt="arrow" class="arrow">
    <div class="step">
        <img src="<?php echo base_url('assets/images/img_admin/ocular-visit.png'); ?>" alt="Ocular Visit">
        <p>Ocular Visit</p>
        <span class="square orange"></span>
    </div>
    <img src="<?php echo base_url('assets/images/img_admin/double-arrow.svg'); ?>" alt="arrow" class="arrow">
    <div class="step">
        <img src="<?php echo base_url('assets/images/img_admin/in-fabrication.png'); ?>" alt="In Fabrication">
        <p>In Fabrication</p>
        <span class="square purple"></span>
    </div>
    <img src="<?php echo base_url('assets/images/img_admin/double-arrow.svg'); ?>" alt="arrow" class="arrow">
    <div class="step">
        <img src="<?php echo base_url('assets/images/img_admin/installed.png'); ?>" alt="Installed">
        <p>Installed</p>
        <span class="square yellow"></span>
    </div>
    <img src="<?php echo base_url('assets/images/img_admin/double-arrow.svg'); ?>" alt="arrow" class="arrow">
    <div class="step">
        <img src="<?php echo base_url('assets/images/img_admin/completed.png'); ?>" alt="Completed">
        <p>Completed</p>
        <span class="square green"></span>
    </div>
</div>



    <!-- Appointments Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Assigned Staff</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="client-cell">Client A</td>
                    <td class="status-cell"><span class="tag orange">Ocular Visit</span></td>
                    <td class="date-cell">5/30/2025 – 9:00 AM</td>
                    <td>Engr. Cruz</td>
                    <td class="progress-cell"><span class="status green"></span> Complete</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="client-cell">Client B</td>
                    <td class="status-cell"><span class="tag purple">Fabrication</span></td>
                    <td class="date-cell">6/1/2025 – 10:00 AM</td>
                    <td>J. Santos</td>
                    <td class="progress-cell"><span class="status yellow"></span> In Progress</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="client-cell">Client C</td>
                    <td class="status-cell"><span class="tag teal">Installed</span></td>
                    <td class="date-cell">7/15/2025 – 10:00 AM</td>
                    <td>M. Lopez</td>
                    <td class="progress-cell"><span class="status green"></span> Complete</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="client-cell">Client D</td>
                    <td class="status-cell"><span class="tag orange">Ocular Visit</span></td>
                    <td class="date-cell">7/21/2025 – 10:00 AM</td>
                    <td>J. Santos</td>
                    <td class="progress-cell"><span class="status red"></span> Cancelled</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td class="client-cell">Client E</td>
                    <td class="status-cell"><span class="tag teal">Installed</span></td>
                    <td class="date-cell">8/27/2025 – 10:00 AM</td>
                    <td>Engr. Cruz</td>
                    <td class="progress-cell"><span class="status yellow"></span> In Progress</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td class="client-cell">Client F</td>
                    <td class="status-cell"><span class="tag blue">Order Placed</span></td>
                    <td class="date-cell">9/10/2025 – 10:00 AM</td>
                    <td>J. Santos</td>
                    <td class="progress-cell"><span class="status green"></span> Complete</td>
                    <td><button class="edit-progress-btn">Edit Progress</button></td>
                </tr>
            </tbody>

        </table>
    </div> <!-- closes table-container -->
    <!-- Pagination -->
    <div class="pagination">
        <span>Showing 1-4 of 255 items</span>
        <div class="pagination-controls">
            <button class="page-btn-pagination prev">&lt;</button>
            <span class="page-number active">1</span>
            <span class="page-number">2</span>
            <button class="page-btn-pagination next">&gt;</button>
        </div>
    </div>
</section> <!-- closes appointment-section-main -->

<!-- Calendar Section -->
<section class="calendar-container">
    <div class="calendar-header">
        <h3 id="calendar-month-year"></h3>
        <div class="calendar-controls">
            <button class="today-btn" onclick="goToToday()">Today</button>
            <button class="page-btn" onclick="prevMonth()">❮</button>
            <button class="page-btn" onclick="nextMonth()">❯</button>
        </div>
    </div>

    <table class="calendar">
        <thead>
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>
        </thead>
        <tbody id="calendar-body">
            <!-- Days will be injected by JavaScript -->
        </tbody>
    </table>
</section>
</main>
</div> <!-- closes container -->

<!-- Overlay & Popup -->
<div class="overlay" id="editProgressPopupOverlay">
    <div class="popup">
        <div class="popup-header">
            <h2>Project Progress</h2>
            <span class="close-btn" onclick="closePopup()">&times;</span>
        </div>
        <div class="popup-content">
            <h3>Project: <input type="text" value="Aluminum Kitchen Cabinet"></h3>
            <form>
                <label>Client</label>
                <input type="text" value="Client A">


                <label>Service</label>
                <select>
                    <option>Order Placed</option>
                    <option>Ocular Visit</option>
                    <option>In Fabrication</option>
                    <option>Installed</option>
                    <option selected>Completed</option>
                </select>

                <label>Date</label>
                <input type="date" value="2025-05-30">

                <label>Assigned Staff</label>
                <input type="text" value="Engr. Cruz">

                <label>Status</label>
                <select>
                    <option selected>Completed</option>
                    <option>In Progress</option>
                    <option>Cancelled</option>
                </select>

                <label>Notes</label>
                <textarea placeholder="empty..."></textarea>
            </form>

            <div class="btn-group">
                <button type="button" class="save-btn">Save Changes</button>
                <button type="button" class="delete-btn">Delete Project</button>
            </div>

            <!-- Cancel button below -->
            <div class="cancel-container">
                <button type="button" class="cancel-btn" onclick="closePopup()">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Overlay & Popup -->
<div class="overlay" id="addAppointmentPopupOverlay">
    <div class="popup">
        <div class="popup-header">
            <h2>Add Project Progress</h2>
            <span class="close-btn" onclick="closePopup()">&times;</span>
        </div>
        <div class="popup-content">
            <h3>Project: <input type="text" placeholder="Enter project name"></h3>
            <form>
                <label>Client</label>
                <input type="text" placeholder="Enter client name">

                <label>Service</label>
                <select>
                    <option selected disabled>Select</option>
                    <option>Order Placed</option>
                    <option>Ocular Visit</option>
                    <option>In Fabrication</option>
                    <option>Installed</option>
                    <option>Completed</option>
                </select>

                <label>Date</label>
                <input type="date">

                <label>Assigned Staff</label>
                <input type="text" placeholder="Enter staff name">

                <label>Status</label>
                <select>
                    <option selected disabled>Select</option>
                    <option>Completed</option>
                    <option>In Progress</option>
                    <option>Cancelled</option>
                </select>

                <label>Notes</label>
                <textarea placeholder="empty..."></textarea>

                <div class="btn-group">
                    <button type="button" class="save-btn">Add Progress</button>
                    <button type="button" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/Glassify/assets/js/calendar.js"></script>
<script src="/Glassify/assets/js/side-popup-appointment.js"></script>
<script src="/Glassify/assets/js/filter-status.js"></script>
