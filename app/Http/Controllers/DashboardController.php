<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Village;
use App\Models\WasteBank;
use App\Models\WasteEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user_id = Auth::user()->id;
        // YRPW
        $villages = Village::count();
        $waste_banks = WasteBank::count();
        $customers = Customer::count();
        $users = User::count();

        // TPS3R
        Carbon::setLocale('id');
        $month = Carbon::now()->translatedFormat('F');
        $year = Carbon::now()->format('Y');
        $countCustomers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })->count();

        $customersUnpaid = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->whereDoesntHave('waste_payments', function (Builder $query) use ($month, $year) {
                $query->where('month_payment', '=', $month)
                    ->where('year_payment', '=', $year);
            })
            ->count();

        $customersPaid = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->whereHas('waste_payments', function (Builder $query) use ($month, $year) {
                $query->where('month_payment', $month)
                    ->where('year_payment', $year);
            })->count();

        return view('dashboard-new.index', [
            'yrpw' => [
                'villages' => $villages,
                'waste_banks' => $waste_banks,
                'customers' => $customers,
                'users' => $users
            ],

            'tps3r' => [
                'customers' => $countCustomers,
                'paid' => $customersPaid,
                'unpaid' => $customersUnpaid,
                'current_month' => $month
            ]
        ]);
    }

    public function getAverageTonaseByCurrentDate()
    {
        $currentDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $avgOrganic = WasteEntry::whereDate('created_at', $currentDate)->avg('waste_organic');
        $avgAnorganic = WasteEntry::whereDate('created_at', $currentDate)->avg('waste_anorganic');
        $avgResidue = WasteEntry::whereDate('created_at', $currentDate)->avg('waste_residue');

        return response()->json([
            'avg_organic' => round($avgOrganic),
            'avg_anorganic' => round($avgAnorganic),
            'avg_residue' => round($avgResidue),
        ]);
    }

    public function getAverageTonase()
    {
        $currentDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $startOfMonth =  Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');

        $avgOrganic = WasteEntry::whereBetween('created_at', [$startOfMonth, $currentDate])->avg('waste_organic');
        $avgAnorganic = WasteEntry::whereBetween('created_at', [$startOfMonth, $currentDate])->avg('waste_anorganic');
        $avgResidue = WasteEntry::whereBetween('created_at', [$startOfMonth, $currentDate])->avg('waste_residue');

        return response()->json([
            'avg_organic' => round($avgOrganic),
            'avg_anorganic' => round($avgAnorganic),
            'avg_residue' => round($avgResidue),
        ]);
    }
}
