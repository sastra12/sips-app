<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Models\Customer;
use App\Models\WastePayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class GarbageCollectionFeeController extends Controller
{

    public function index()
    {
    }

    public function monthlyBillData()
    {
        $user_id = Auth::user()->id;
        $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->orderByDesc('created_at')
            ->get();
        return Datatables::of($customers)
            ->addIndexColumn()
            ->make();
    }

    public function monthlyBill()
    {
        return view('admin-tps3r.manage-garbage-collection-fee.monthly-bill');
    }

    public function checkMonthlyBill()
    {
        return view('admin-tps3r.manage-garbage-collection-fee.check-monthly-bill');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
