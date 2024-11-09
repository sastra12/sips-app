<?php

namespace App\Http\Controllers;

use App\Exports\CustomerUnpaidMonthlyBillExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Models\Customer;
use App\Models\User;
use App\Models\WasteBank;
use App\Models\WastePayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GarbageCollectionFeeController extends Controller
{

    public function index()
    {
    }

    public function downloadDetailPaidCustomerByTPS3R(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'year_payment' => 'required|digits:4',
            ],
            [
                'year_payment.required' => 'Data tahun tidak boleh kosong',
                'year_payment.digits' => 'Data tahun harus terdiri dari :digits angka',
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $year_payment = $request->input('year_payment');
            $customer_id = $request->input('customerId');
            $customers = Customer::select(['customer_id', 'customer_name', 'customer_address', 'waste_id', 'customer_community_association', 'customer_neighborhood'])
                ->with(['waste_payments' => function ($query) use ($year_payment) {
                    $query->where('year_payment', '=', $year_payment)
                        ->orderByRaw("FIELD(month_payment, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember') ASC");
                }, 'waste_bank' => function ($query) {
                    $query->select('waste_name', 'waste_bank_id');
                }])
                ->where('customer_id', $customer_id)
                ->first();

            return view('admin-tps3r-new.manage-garbage-collection-fee.customer-payment-record-pdf', [
                'customer' => $customers,
                'waste_name' => $customers->waste_bank->waste_name,
                'year' => $year_payment
            ]);
        }
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
                return '<button onclick="addWastePayment(' . $customer->customer_id . ', ' . $customer->rubbish_fee . ')" class="btn btn-sm custom-btn-sm btn-info">Tambah Pembayaran</button>
                <a onclick="detailsWastePayment(' . $customer->customer_id . ')" class="btn btn-sm custom-btn-sm btn-success">Rincian Pembayaran</a>
                ';
            })
            ->make();
    }

    public function monthlyBill()
    {
        return view('admin-tps3r-new.manage-garbage-collection-fee.monthly-bill');
    }

    public function checkMonthlyBillPaidView()
    {
        $bankId = WasteBank::query()->whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', Auth::user()->id);
        })->first();

        $paymentTotal = Customer::query()->where('waste_id', '=', $bankId->waste_bank_id)->sum('rubbish_fee');

        return view(
            'admin-tps3r-new.manage-garbage-collection-fee.check-monthly-bill-paid',
            [
                'paymentTotal' => $paymentTotal
            ]
        );
    }

    public function checkMonthlyBillUnpaidView()
    {
        return view('admin-tps3r-new.manage-garbage-collection-fee.check-monthly-bill-unpaid');
    }

    public function checkMonthlyBillPaid(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'month_payment' => 'required',
                'year_payment' => 'required|digits:4',
            ],
            [
                'month_payment.required' => 'Data bulan tidak boleh kosong',
                'year_payment.required' => 'Data tahun tidak boleh kosong',
                'year_payment.digits' => 'Data tahun harus terdiri dari :digits angka',
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
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
                ->withSum(['waste_payments as total_due_this_month' => function ($query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', '=', $month_payment)
                        ->where('year_payment', '=', $year_payment);
                }], 'amount_due')
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
        $validated = Validator::make(
            $request->all(),
            [
                'month_payment' => 'required',
                'year_payment' => 'required|digits:4',
            ],
            [
                'month_payment.required' => 'Data bulan tidak boleh kosong',
                'year_payment.required' => 'Data tahun tidak boleh kosong',
                'year_payment.digits' => 'Data tahun harus terdiri dari :digits angka',
            ]
        );

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
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

    public function exportCustomerUnpaidMonthlyBill(Request $request)
    {
        $customersData = [];
        $month_payment = $request->input('month_payment');
        $year_payment = $request->input('year_payment');
        $user_id = Auth::user()->id;
        Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->whereDoesntHave('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                $query->where('month_payment', $month_payment)
                    ->where('year_payment', $year_payment);
            })
            ->with(['waste_bank' => function ($query) {
                $query->select('waste_bank_id', 'waste_name');
            }])
            ->orderByDesc('created_at')
            ->chunk(500, function ($customers) use (&$customersData) {
                foreach ($customers as $customer) {
                    $customersData[] = [
                        'waste_name' => $customer->waste_bank->waste_name,
                        'customer_name' => $customer->customer_name,
                        'customer_address' => $customer->customer_address,
                        'customer_neighborhood' => $customer->customer_neighborhood,
                        'customer_community_association' => $customer->customer_community_association,
                        'rubbish_fee' => $customer->rubbish_fee,
                        'monthly_bill_status' => "Belum Lunas",
                        'created_at' => $customer->created_at,
                    ];
                }
            });
        return Excel::download(new CustomerUnpaidMonthlyBillExport($customersData), 'Data Pelanggan Yang Belum Lunas Bulan ' . $month_payment . ' Tahun ' . $year_payment . '.xlsx');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'month' => 'required',
                'year' => 'required|digits:4',
                'amount_due' => 'required|numeric|min:4'
            ],
            // Custom Error Messages Validation
            [
                'month.required' => 'Data bulan tidak boleh kosong',
                'year.required' => 'Data tahun tidak boleh kosong',
                'year.digits' => 'Data tahun harus terdiri dari :digits angka',
                'amount_due.required' => 'Data tagihan tidak boleh kosong',
                'amount_due.numeric' => 'Data tagihan harus angka',
                'amount_due.min' => 'Data tagihan minimal terdiri dari :min angka',
            ]
        );
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
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
                    'status' => 'Failed',
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
