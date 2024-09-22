 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link">
         <img src="{{ asset('AdminLTE-3/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
         <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="{{ asset('AdminLTE-3/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                     alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block">Sastra</a>
             </div>
         </div>
         <!-- Sidebar Menu -->
         <nav class="mt-2">

             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">
                 @if (Auth::user()->role_id == 1)
                     <li class="nav-item">
                         <a href="" class="nav-link">
                             <i class="nav-icon fas fa-tachometer-alt"></i>
                             <p>
                                 Dashboard
                             </p>
                         </a>
                     </li>
                     <li class="nav-header">Manajemen</li>
                     <li class="nav-item">
                         <a href="{{ route('village.index') }}" class="nav-link">
                             <i class="fa fa-cube"></i>
                             <p>
                                 Tambahkan Desa Dampingan
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('user.index') }}" class="nav-link">
                             <i class="fa fa-cubes"></i>
                             <p>
                                 Tambahkan Admin
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('waste-bank.index') }}" class="nav-link">
                             <i class="fa fa-cubes"></i>
                             <p>
                                 Tambahkan TPS3R
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('customer.index') }}" class="nav-link">
                             <i class="fa fa-id-card"></i>
                             <p>
                                 Manajemen Pelanggan
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('waste-entri.index') }}" class="nav-link">
                             <i class="fa fa-truck"></i>
                             <p>
                                 Manajemen Tonase Sampah
                             </p>
                         </a>
                     </li>
                 @elseif (Auth::user()->role_id == 2)
                     <li class="nav-item">
                         <a href="{{ route('waste-entri-user.index') }}" class="nav-link">
                             <i class="fa fa-truck"></i>
                             <p>
                                 Manajemen Tonase
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('admin-tps3r-customer.index') }}" class="nav-link">
                             <i class="fa fa-truck"></i>
                             <p>
                                 Manajemen Pelanggan
                             </p>
                         </a>
                     </li>
                 @else
                     <li class="nav-item">
                         <a href="{{ route('view-tonase-facilitator') }}" class="nav-link">
                             <i class="fa fa-truck"></i>
                             <p>
                                 Data Tonase
                             </p>
                         </a>
                     </li>
                 @endif
                 <li class="nav-item">
                     <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                         <i class="fa fa-cog"></i>
                         <p>
                             Logout
                         </p>
                     </a>
                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
     <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none">
         @csrf
     </form>
 </aside>
