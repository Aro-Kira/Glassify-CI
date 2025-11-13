document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("menu-toggle");
    const container = document.getElementById("admin-container");

    console.log("DOM loaded");
    console.log("Sidebar element:", sidebar);
    console.log("Toggle button:", toggle);
    console.log("Container:", container);

    if (toggle && sidebar && container) {
        toggle.addEventListener("click", () => {
            console.log("Toggle clicked!");
            sidebar.classList.toggle("collapsed");
            container.classList.toggle("sidebar-collapsed");

            // Check if classes were added
            console.log("Sidebar classes:", sidebar.className);
            console.log("Container classes:", container.className);
        });
    } else {
        console.warn("Some elements are missing. Collapse will not work.");
    }
});
