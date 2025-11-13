document.addEventListener('DOMContentLoaded', async function () {

    // ======================
    // 1️⃣ Variables
    // ======================
    const tbody = document.querySelector('.table-container tbody');
    const foundText = document.querySelector('.found-text');
    const tabButtons = document.querySelectorAll('.order-tabs .tab-button');
    const searchInput = document.querySelector('.search-container input');
    const searchBtn = document.querySelector('.search-container .search-button');

    const actionMenu = document.getElementById('actionMenu');
    const popup = document.getElementById('orderPopup');
    const closeBtn = document.getElementById('closePopup');

    const btn = document.getElementById('calendar-btn');
    const dropdown = document.getElementById('calendar-dropdown');
    const monthEl = document.getElementById('month');
    const yearEl = document.getElementById('year');
    const dropdownMonth = document.getElementById('dropdown-month');
    const dropdownYear = document.getElementById('dropdown-year');
    const resetCalendarBtn = document.getElementById('reset-calendar');

    let allOrders = [];
    let filteredOrders = [];
    let searchQuery = '';
    let activeFilter = 'all orders';
    let selectedMonth = null;
    let selectedYear = null;

    let currentPage = 1;
    const itemsPerPage = 10;

    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    // ======================
    // 2️⃣ Load JSON Data
    // ======================
    try {
        const response = await fetch(ordersJsonUrl);
        if (!response.ok) throw new Error("Network response was not ok");
        allOrders = await response.json();
        filteredOrders = [...allOrders];
        applyFilters();
    } catch (error) {
        console.error("Error loading orders JSON:", error);
    }

    // ======================
    // 3️⃣ Apply Combined Filters
    // ======================
  function applyFilters() {
    filteredOrders = allOrders.filter(order => {
        // Map tab filter to actual order status
        let status = order.status.toLowerCase();
        let statusFilter = null;

        switch (activeFilter) {
            case 'all orders':
                statusFilter = null; // no filter
                break;
            case 'completed':
                statusFilter = 'confirmed';
                break;
            case 'pending':
                statusFilter = 'pending';
                break;
            case 'cancel':
                statusFilter = 'canceled';
                break;
        }

        let statusMatch = !statusFilter || status === statusFilter;

        // Search filter
        let searchMatch = order.product_name.toLowerCase().includes(searchQuery) ||
                          order.order_id.toLowerCase().includes(searchQuery);

        // Calendar filter
        const dateParts = order.date.split('/'); // "dd/mm/yyyy"
        const orderMonth = parseInt(dateParts[1]) - 1;
        const orderYear = parseInt(dateParts[2]);
        const monthMatch = selectedMonth === null || orderMonth === selectedMonth;
        const yearMatch = selectedYear === null || orderYear === selectedYear;

        return statusMatch && searchMatch && monthMatch && yearMatch;
    });

    currentPage = 1; // Reset page whenever filters change
    renderTable();
}

    // ======================
    // 4️⃣ Render Table with Pagination
    // ======================
    function renderTable() {
        tbody.innerHTML = '';

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredOrders.slice(start, end);

        paginatedData.forEach((order, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${start + index + 1}</td>
                <td>${order.order_id}</td>
                <td>${order.product_name}</td>
                <td>${order.address}</td>
                <td>${order.date}</td>
                <td>₱${parseFloat(order.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                <td><span class="status-badge ${order.status.toLowerCase()}">${order.status}</span></td>
                <td class="action-cell">⋮</td>
            `;
            tbody.appendChild(tr);
        });

        foundText.textContent = `${filteredOrders.length} Orders found`;

        attachActionMenu();
        attachPopupListeners();
        updatePaginationControls();
    }

    function updatePaginationControls() {
        const pagination = document.querySelector('.pagination-controls');
        pagination.innerHTML = '';
        const totalPages = Math.ceil(filteredOrders.length / itemsPerPage);

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => { currentPage--; renderTable(); });
        pagination.appendChild(prevBtn);

        // Page buttons
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            if (i === currentPage) btn.classList.add('active');
            btn.addEventListener('click', () => { currentPage = i; renderTable(); });
            pagination.appendChild(btn);
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => { currentPage++; renderTable(); });
        pagination.appendChild(nextBtn);
    }

    // ======================
    // 5️⃣ Tab Filters
    // ======================
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.textContent.trim().toLowerCase();
            applyFilters();
        });
    });

    // ======================
    // 6️⃣ Search Filters
    // ======================
    searchBtn.addEventListener('click', () => {
        searchQuery = searchInput.value.trim().toLowerCase();
        applyFilters();
    });

    searchInput.addEventListener('keyup', e => {
        if (e.key === 'Enter') {
            searchQuery = searchInput.value.trim().toLowerCase();
            applyFilters();
        }
    });

    // ======================
    // 7️⃣ Calendar Filters
    // ======================
    function updateCalendarDisplay() {
        monthEl.textContent = selectedMonth !== null ? monthNames[selectedMonth] : 'All';
        dropdownMonth.textContent = selectedMonth !== null ? monthNames[selectedMonth] : 'All';
        yearEl.textContent = selectedYear !== null ? selectedYear : '';
        dropdownYear.textContent = selectedYear !== null ? selectedYear : '';
    }

    function changeMonth(offset) {
        currentMonth += offset;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        selectedMonth = currentMonth;
        selectedYear = currentYear;
        updateCalendarDisplay();
        applyFilters();
    }

    function changeYear(offset) {
        currentYear += offset;
        selectedMonth = currentMonth;
        selectedYear = currentYear;
        updateCalendarDisplay();
        applyFilters();
    }

    btn.addEventListener('click', () => {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    resetCalendarBtn.addEventListener('click', () => {
        selectedMonth = null;
        selectedYear = null;
        updateCalendarDisplay();
        applyFilters();
        dropdown.style.display = 'none'; // close dropdown after reset
    });

    window.prevMonth = () => changeMonth(-1);
    window.nextMonth = () => changeMonth(1);
    window.prevYear = () => changeYear(-1);
    window.nextYear = () => changeYear(1);

    updateCalendarDisplay();

    // ======================
    // 8️⃣ Action Menu
    // ======================
    function attachActionMenu() {
        const actionCells = document.querySelectorAll('.action-cell');
        actionCells.forEach(cell => {
            cell.addEventListener('click', e => {
                e.stopPropagation();
                const rect = cell.getBoundingClientRect();
                actionMenu.style.top = `${window.scrollY + rect.bottom}px`;
                actionMenu.style.left = `${window.scrollX + rect.left}px`;
                actionMenu.style.display = 'block';
            });
        });
    }

    document.addEventListener('click', e => {
        if (!actionMenu.contains(e.target)) {
            actionMenu.style.display = 'none';
        }
    });

    // ======================
    // 9️⃣ Popup Modal
    // ======================
    function attachPopupListeners() {
        const reviewBtns = document.querySelectorAll('.btn-review');
        reviewBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                popup.style.display = 'flex';
            });
        });

        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });

        window.addEventListener('click', e => {
            if (e.target === popup) {
                popup.style.display = 'none';
            }
        });
    }

});
