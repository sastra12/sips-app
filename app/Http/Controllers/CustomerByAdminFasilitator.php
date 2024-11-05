<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\WasteBank;
use DataTables;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerByAdminFasilitator extends Controller
{
    public function wasteBankCustomerData()
    {
        $listdata = WasteBank::query()
            ->select("waste_bank_id", "waste_name", "village_id", "created_at")
            ->with(['village' => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc("created_at")
            ->get();

        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a href="' . route('customer-details-facilitator.view', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm custom-btn-sm btn-info">Customer Details</a>';
            })
            ->addColumn('village_name', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    public function index()
    {
        return view('admin-fasilitator-new.customer-data.index');
    }

    public function customerDataFacilitator(Request $request)
    {
        $waste_id = $request->input('bankId');
        $listdata = Customer::query()
            ->where('waste_id', '=', $waste_id)
            ->orderByDesc('created_at')
            ->get();
        return Datatables::of($listdata)
            ->addIndexColumn()
            ->make();
    }

    public function viewCustomerDetailsFacilitator()
    {
        $waste_banks = WasteBank::query()->get();
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-fasilitator-new.customer-data.customer-details-facilitator', [
            'customer_status' => $customer_status,
            'waste_banks' => $waste_banks
        ]);
    }

    public function exportCustomerByFacilitator(Request $request)
    {
        $customersData = [];
        Customer::where('waste_id', $request->input('bankId'))
            ->with(['waste_bank:waste_bank_id,waste_name'])
            ->orderByDesc('created_at')
            ->chunk(500, function ($customers) use (&$customersData) {
                foreach ($customers as $customer) {
                    $customersData[] = [
                        'customer_name' => $customer->customer_name,
                        'waste_name' => $customer->waste_bank->waste_name,
                        'customer_address' => $customer->customer_address,
                        'customer_neighborhood' => $customer->customer_neighborhood,
                        'customer_community_association' => $customer->customer_community_association,
                        'rubbish_fee' => $customer->rubbish_fee,
                        'customer_status' => $customer->customer_status,
                        'created_at' => $customer->created_at,
                    ];
                }
            });
        return Excel::download(new CustomersExport($customersData), 'Data Pelanggan.xlsx');
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
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
