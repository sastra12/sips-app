@extends('layouts-new.master')

@section('content')
    <div class="main-title">
        <span class="material-icons-outlined"> space_dashboard </span>
        <span class="title">Dashboard</span>
    </div>
    @if (Auth::user()->role_id == 1)
        <div class="main-cards">
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Desa Dampingan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['villages'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">TPS3R</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['waste_banks'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['customers'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Jumlah Admin</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $yrpw['users'] }}</span>
            </div>
        </div>
    @elseif (Auth::user()->role_id == 2)
        <div class="main-cards-second">
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Jumlah Pelanggan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['customers'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan Lunas {{ $tps3r['current_month'] }}</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['paid'] }}</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan Belum Lunas {{ date('F') }}</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">{{ $tps3r['unpaid'] }}</span>
            </div>
        </div>
    @endif

    <div class="content">

    </div>
@endsection
