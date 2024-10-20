<header class="header">
    <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined"> menu </span>
    </div>
    <div class="header-right">
        <span class="material-icons-outlined account" onclick="toggleDropdownHeader()">
            account_circle
        </span>
        <ul class="dropdown-menu">
            <li style="cursor: default">Selamat datang, {{ Auth::user()->name }}</li>
            <li><a href="#">Profile</a></li>
            <li><a href="#" onclick="document.getElementById('logout-form').submit()">Logout</a></li>
        </ul>
    </div>
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none">
        @csrf
    </form>
</header>
