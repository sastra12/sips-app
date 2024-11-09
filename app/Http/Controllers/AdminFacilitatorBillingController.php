<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerUnpaidMonthlyBillExport;

class AdminFacilitatorBillingController extends Controller
{
    public function data()
    {
        $wasteBank = WasteBank::query()
            ->select("waste_bank_id", "waste_name", "village_id")
            ->with(['village' => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc('created_at')
            ->get();
        return DataTables::of($wasteBank)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a href="' . route('billing-customer-details-facilitator', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm custom-btn-sm btn-info">Detail Iuran Pelanggan</a>';
            })
            ->addColumn('village_name', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    public function index()
    {
        return view('admin-fasilitator-new.billing-report.index');
    }


    public function billCustomerDetail(Request $request)
    {
        $bankId = $request->input('bankId');
        $paymentTotal = Customer::query()->where('waste_id', '=', $bankId)->sum('rubbish_fee');
        $wasteName = WasteBank::query()->select('waste_name')->where('waste_bank_id', '=', $bankId)->first();
        // dd($wasteName);
        return view('admin-fasilitator-new.billing-report.billing-customer-detail-view', [
            'waste_name' => $wasteName,
            'paymentTotal' => $paymentTotal
        ]);
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
            $bankId = $request->input('bankId');
            $month_payment = $request->input('month_payment');
            $year_payment = $request->input('year_payment');
            $customers = Customer::query()
                ->select("customer_id", "customer_address", "customer_name", "customer_neighborhood", "customer_community_association")
                ->whereHas('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', '=', $month_payment)
                        ->where('year_payment', '=', $year_payment);
                })
                ->withSum(['waste_payments as total_due_this_month' => function ($query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', '=', $month_payment)
                        ->where('year_payment', '=', $year_payment);
                }], 'amount_due')
                ->where('waste_id', '=', $bankId)
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
            $bankId = $request->input('bankId');
            $month_payment = $request->input('month_payment');
            $year_payment = $request->input('year_payment');
            $customers = Customer::query()
                ->whereDoesntHave('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                    $query->where('month_payment', '=', $month_payment)
                        ->where('year_payment', '=', $year_payment);
                })
                ->where('waste_id', '=', $bankId)
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
        $bankId = $request->input('bankId');
        $month_payment = $request->input('month_payment');
        $year_payment = $request->input('year_payment');

        Customer::query()
            ->whereDoesntHave('waste_payments', function (Builder $query) use ($month_payment, $year_payment) {
                $query->where('month_payment', '=', $month_payment)
                    ->where('year_payment', '=', $year_payment);
            })
            ->where('waste_id', '=', $bankId)
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
}
