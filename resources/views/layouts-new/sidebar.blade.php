<aside id="sidebar">
    <div class="sidebar-title">
        <div class="sidebar-brand">
            <div class="brand-image">
                <img src="images/yrpw.jpg" alt="" />
            </div>
        </div>
        <span class="material-icons-outlined" onclick="closeSidebar()">
            close
        </span>
    </div>

    <ul class="sidebar-list">
        @if (Auth::user()->role_id == 1)
            <li class="sidebar-list-item">
                <a href="{{ route('village.index') }}">
                    <span class="material-icons-outlined">
                        holiday_village
                    </span> Tambah Desa Dampingan
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('user.index') }}">
                    <span class="material-icons-outlined">
                        people
                    </span> Tambahkan Admin
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('waste-bank.index') }}">
                    <span class="material-icons-outlined">
                        dashboard
                    </span> Tambahkan TPS3R
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('customer.index') }}">
                    <span class="material-icons-outlined">
                        people
                    </span> Manajemen Pelanggan
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('waste-entri.index') }}">
                    <span class="material-icons-outlined">
                        recycling
                    </span> Manajemen Tonase
                </a>
            </li>
        @elseif (Auth::user()->role_id == 2)
            <li class="sidebar-list-item">
                <a href="{{ route('waste-entri-user.index') }}">
                    <span class="material-icons-outlined">
                        recycling
                    </span> Manajemen Tonase
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('admin-tps3r-customer.index') }}">
                    <span class="material-icons-outlined">
                        people
                    </span> Manajemen Pelanggan
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('monthly-bill.view') }}">
                    <span class="material-icons-outlined">
                        payments
                    </span> Tagihan Bulanan
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('viewMonthlyBillPaid') }}">
                    <span class="material-icons-outlined">
                        done
                    </span> Tagihan Lunas
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('viewMonthlyBillUnpaid') }}">
                    <span class="material-icons-outlined">
                        unpublished
                    </span> Tagihan Belum Lunas
                </a>
            </li>
        @else
            <li class="sidebar-list-item">
                <a href="{{ route('view-waste-bank-facilitator.view') }}">
                    <span class="material-icons-outlined">
                        recycling
                    </span> Data Tonase
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('view-waste-bank-facilitator.view') }}">
                    <span class="material-icons-outlined">
                        recycling
                    </span> Data Iuran
                </a>
            </li>
            <li class="sidebar-list-item">
                <a href="{{ route('customer-by-admin-facilitator.index') }}">
                    <span class="material-icons-outlined">
                        people
                    </span> Data Pelanggan
                </a>
            </li>
        @endif

        <li class="sidebar-list-item">
            <a href="#" onclick="document.getElementById('logout-form').submit()">
                <span class="material-icons-outlined">
                    logout
                </span> Logout
            </a>
        </li>
    </ul>

    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none">
        @csrf
    </form>
</aside>

@push('script')
    <script>
        let sidebarBrand = document.querySelector(".sidebar-brand");
        sidebarBrand.addEventListener("click", function() {
            window.location.href = "{{ route('dashboard') }}";
        })
    </script>
@endpush
