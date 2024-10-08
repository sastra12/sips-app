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

    public function checkMonthlyBillPaidView()
    {
        return view('admin-tps3r.manage-garbage-collection-fee.check-monthly-bill-paid');
    }

    public function checkMonthlyBillUnpaidView()
    {
        return view('admin-tps3r.manage-garbage-collection-fee.check-monthly-bill-unpaid');
    }

    public function checkMonthlyBillPaid(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'month_payment' => 'required',
            'year_payment' => 'required|numeric|min:4',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validated->messages()
            ]);
        } else {
            $month_payment = $request->input('month_payment');
            $year_payment = $request->input('year_payment');
            $user_id = Auth::user()->id;
            $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
                $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            })
                ->whereHas('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', $month_payment)
                        ->where('year_payment', $year_payment);
                })
                ->with(['waste_payments' => function ($query) {
                    $query->select("customer_id", "created_at");
                }])
                ->orderByDesc('created_at')
                ->get();

            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => 'Not Found',
                    'message' => 'Tidak ada data untuk bulan dan tahun yang dipilih.'
                ]);
            }
            // return response()->json($customers);
            return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function ($customer) {
                    return '<button onclick="downloadProofOfPyament(' . $customer->customer_id . ')" class="btn btn-xs btn-info">Download Bukti Pembayaran</button>';
                })
                ->addColumn('badge_success', function ($customer) {
                    return '<span class="badge badge-success">Lunas</span>';
                })
                ->addColumn('paid_date', function ($customer) {
                    return date('d F Y', strtotime($customer->waste_payments[0]->created_at));
                })
                ->rawColumns(['badge_success', 'action'])
                ->make();
        }
    }

    public function checkMonthlyBillUnpaid(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'month_payment' => 'required',
            'year_payment' => 'required|numeric|min:4',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed',
                'errors' => $validated->messages()
            ]);
        } else {
            $month_payment = $request->input('month_payment');
            $year_payment = $request->input('year_payment');
            $user_id = Auth::user()->id;
            $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
                $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            })
                ->whereDoesntHave('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', $month_payment)
                        ->where('year_payment', $year_payment);
                })
                ->orderByDesc('created_at')
                ->get();

            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => 'Not Found',
                    'message' => 'Tidak ada data untuk bulan dan tahun yang dipilih.'
                ]);
            }
            return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('badge_danger', function ($customer) {
                    return '<span class="badge badge-danger">Belum Lunas</span>';
                })
                ->rawColumns(['badge_danger'])
                ->make();
        }
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
