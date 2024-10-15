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
        <div class="main-cards">
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Desa Dampingan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">80</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">TPS3R</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">100</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Pelanggan</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">249</span>
            </div>
            <div class="card-box">
                <div class="card-inner">
                    <span class="card-box-title">Jumlah Admin</span>
                    <span class="material-icons-outlined"> inventory_2 </span>
                </div>
                <span class="card-box-subtitle">150</span>
            </div>
        </div>
    @endif

    <div class="content">

    </div>
@endsection
