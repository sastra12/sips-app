// Sidebar Toggle
let sidebarOpen = false;
let sidebarMenu = document.getElementById("sidebar");
const dropdown = document.querySelector(".header-right .dropdown-menu");

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

function toggleDropdownHeader() {
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
}

window.onclick = function (event) {
    if (!event.target.matches(".material-icons-outlined.account")) {
        dropdown.style.display = "none";
    }
};
