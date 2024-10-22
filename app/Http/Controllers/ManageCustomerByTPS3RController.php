<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ManageCustomerByTPS3RController extends Controller
{

    public function data()
    {
        $user_id = Auth::user()->id;
        $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name'])
            ->orderByDesc('created_at')
            ->get();
        // return response()->json($customers);
        return Datatables::of($customers)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                <button onclick="deleteDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>  
            ';
            })
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->make();
    }

    public function index()
    {
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-tps3r-new.manage-customer.index', [
            'customer_status' => $customer_status,
        ]);
    }


    public function create()
    {
        //
    }

    public function store(CustomerRequest $request)
    {
        // Dapatkan id waste_bank berdasarkan user yang login
        $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', '=', Auth::user()->id);
        })
            ->select('waste_bank_id')
            ->get();

        $validated = $request->validated();
        $data = new Customer();
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->waste_id = $waste_id[0]->waste_bank_id;
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Added Data'
        ]);
    }

    public function show($id)
    {
        $customer = Customer::query()->find($id);
        return response()->json($customer);
    }

    public function edit($id)
    {
        //
    }

    public function update(CustomerRequest $request, $id)
    {
        $validated = $request->validated();
        $data = Customer::query()->find($id);
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    public function destroy($id)
    {
        $data = Customer::query()->find($id);
        $data->delete();
    }

    public function exportCustomer()
    {
        return Excel::download(new CustomersExport($this->getCustomer()), 'Data Pelanggan.xlsx');
    }

    protected function getCustomer()
    {
        $customersData = [];
        $user_id = Auth::user()->id;
        Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
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
        return $customersData;
    }
}
