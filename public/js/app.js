// Sidebar Toggle
let sidebarOpen = false;
let sidebarMenu = document.getElementById("sidebar");

function openSidebar() {
    if (!sidebarOpen) {
        sidebarMenu.classList.add("sidebar-responsive");
        sidebarOpen = true;
    }
}

function closeSidebar() {
    if (sidebarOpen) {
        sidebarMenu.classList.remove("sidebar-responsive");
        sidebarOpen = false;
    }
}
