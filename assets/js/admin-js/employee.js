document.addEventListener("DOMContentLoaded", () => {
    // --- Elements ---
    const roleTabs = document.querySelectorAll(".filter-tabs .tab-button");
    const searchInput = document.querySelector(".search-input");
    const searchButton = document.querySelector(".search-button");

    const addUserBtn = document.querySelector(".add-user-button");
    const addUserPopup = document.getElementById("addUserPopupOverlay");
    const editPopup = document.getElementById("editPopupOverlay");
    const closeBtns = document.querySelectorAll(".close-btn");
    const cancelBtns = document.querySelectorAll(".cancel-btn");

    let currentFilter = "all"; // store the current tab filter
    let usersData = []; // users array
    let currentEditIndex = null;

    // --- Load users from PHP JSON ---
    async function loadUsers() {
        try {
            const res = await fetch('/Glassify-CI/EmpCon/get_users');
            usersData = await res.json();
            renderTable();
        } catch (err) {
            console.error("Error loading users:", err);
        }
    }

    // --- Save users to PHP JSON ---
    async function saveUsersToServer() {
        try {
            await fetch('/Glassify-CI/EmpCon/save_users', {
                method: 'POST',
                body: JSON.stringify(usersData),
                headers: { 'Content-Type': 'application/json' }
            });
        } catch (err) {
            console.error("Error saving users:", err);
        }
    }

    // --- Render Table ---
    function renderTable() {
        const tbody = document.querySelector(".table-container table tbody");
        tbody.innerHTML = "";
        usersData.forEach((user, idx) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${user.name}</td>
                <td>${user.role}</td>
                <td>${user.email}</td>
                <td><span class="status ${user.status.toLowerCase()}"></span>${user.status}</td>
                <td><i class="fas fa-edit edit-icon"></i></td>
            `;
            tbody.appendChild(tr);
        });

        // Bind edit icons
        document.querySelectorAll(".edit-icon").forEach((icon, idx) => {
            icon.addEventListener("click", () => openEditPopup(idx));
        });

        filterRows(); // Apply current filter & search
    }

    // --- Filter/Search ---
    function filterRows() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        document.querySelectorAll(".table-container table tbody tr").forEach((row, idx) => {
            const user = usersData[idx];
            const matchesTab = currentFilter === "all" || user.role === currentFilter;
            const matchesSearch = user.name.toLowerCase().includes(searchTerm) || user.email.toLowerCase().includes(searchTerm);
            row.style.display = (matchesTab && matchesSearch) ? "table-row" : "none";
        });
    }

    // --- Tab click ---
    roleTabs.forEach(tab => {
        tab.addEventListener("click", () => {
            roleTabs.forEach(btn => btn.classList.remove("active"));
            tab.classList.add("active");
            currentFilter = tab.getAttribute("data-filter");
            filterRows();
        });
    });

    // --- Search ---
    searchButton.addEventListener("click", filterRows);
    searchInput.addEventListener("keyup", e => {
        if (e.key === "Enter") filterRows();
    });

    // --- Popups ---
    addUserBtn.addEventListener("click", () => addUserPopup.style.display = "flex");
    closeBtns.forEach(btn => btn.addEventListener("click", closePopups));
    cancelBtns.forEach(btn => btn.addEventListener("click", closePopups));

    function closePopups() {
        addUserPopup.style.display = "none";
        editPopup.style.display = "none";
    }

    // --- Open Edit Popup ---
    function openEditPopup(index) {
        currentEditIndex = index;
        const user = usersData[index];
        editPopup.querySelector('input[type="text"]').value = user.name;
        editPopup.querySelector('input[type="email"]').value = user.email;
        editPopup.querySelector('select').value = user.role;
        editPopup.style.display = "flex";
    }

    // --- Add User ---
    document.querySelector("#addUserPopupOverlay .save-btn").addEventListener("click", async () => {
        const popup = addUserPopup;
        const name = popup.querySelector('input[placeholder="Enter full name"]').value.trim();
        const email = popup.querySelector('input[placeholder="Enter email address"]').value.trim();
        const role = popup.querySelector('select').value;
        const status = "Active";

        if (!name || !email || !role) {
            alert("Please fill all required fields!");
            return;
        }

        usersData.push({ name, email, role, status });
        await saveUsersToServer();
        renderTable();
        closePopups();
    });

    // --- Save Edit ---
    document.querySelector("#editPopupOverlay .save-btn").addEventListener("click", async () => {
        if (currentEditIndex === null) return;
        const popup = editPopup;
        const user = usersData[currentEditIndex];
        user.name = popup.querySelector('input[type="text"]').value.trim();
        user.email = popup.querySelector('input[type="email"]').value.trim();
        user.role = popup.querySelector('select').value;
        await saveUsersToServer();
        renderTable();
        closePopups();
    });

    // --- Delete User ---
    document.querySelector("#editPopupOverlay .delete-btn").addEventListener("click", async () => {
        if (currentEditIndex === null) return;
        if (!confirm("Are you sure you want to delete this user?")) return;
        usersData.splice(currentEditIndex, 1);
        await saveUsersToServer();
        renderTable();
        closePopups();
    });

    // --- Initialize ---
    loadUsers();
});
