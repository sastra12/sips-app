<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Village;
use App\Models\WasteBank;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $villages = Village::count();
        $waste_banks = WasteBank::count();
        $customers = Customer::count();
        $users = User::count();

        // TPS3R
        return view('dashboard-new.index', [
            'yrpw' => [
                'villages' => $villages,
                'waste_banks' => $waste_banks,
                'customers' => $customers,
                'users' => $users
            ],
        ]);
    }
}
