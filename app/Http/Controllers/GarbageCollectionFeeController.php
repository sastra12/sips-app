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

    public function customerData()
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
            ->addColumn('action', function ($customer) {
                return '<button onclick="addWastePayment(' . $customer->customer_id . ', ' . $customer->rubbish_fee . ')" class="btn btn-xs btn-info">Tambah Pembayaran</button>';
            })
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
        $validated = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required|numeric|min:4',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            // cek apakah sudah melakukan pembayaran pada bulan yang sama
            $dataExists = WastePayment::where('customer_id', $request->input('customerId'))
                ->where('month_payment', $request->input('month'))
                ->where('year_payment', $request->input('year'))
                ->exists();
            if ($dataExists) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Pembayaran sudah dilakukan pada bulan ini.'
                ]);
            } else {
                $data = new WastePayment();
                $data->customer_id = $request->input('customerId');
                $data->month_payment = $request->input('month');
                $data->year_payment = $request->input('year');
                $data->amount_due = $request->input('amount_due');
                $data->save();
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Success Added Data'
                ]);
            }
        }
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
