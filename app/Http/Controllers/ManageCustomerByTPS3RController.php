<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Validator;
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
                <button onclick="editDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-xs btn-danger">Hapus</button>  
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
        return view('admin-tps3r.manage-customer.index', [
            'customer_status' => $customer_status,
        ]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Dapatkan id waste_bank berdasarkan user yang login
        $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', '=', Auth::user()->id);
        })
            ->select('waste_bank_id')
            ->get();

        $validated = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_neighborhood' => 'required|numeric',
            'customer_community_association' => 'required|numeric',
            'rubbish_fee' => 'required|numeric',
            'customer_status' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new Customer();
            $data->customer_name = $request->input('customer_name');
            $data->customer_address = $request->input('customer_address');
            $data->customer_neighborhood = $request->input('customer_neighborhood');
            $data->customer_community_association = $request->input('customer_community_association');
            $data->rubbish_fee = $request->input('rubbish_fee');
            $data->customer_status = $request->input('customer_status');
            $data->waste_id = $waste_id[0]->waste_bank_id;
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
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

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_neighborhood' => 'required|numeric',
            'customer_community_association' => 'required|numeric',
            'rubbish_fee' => 'required|numeric',
            'customer_status' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = Customer::query()->find($id);
            $data->customer_name = $request->input('customer_name');
            $data->customer_address = $request->input('customer_address');
            $data->customer_neighborhood = $request->input('customer_neighborhood');
            $data->customer_community_association = $request->input('customer_community_association');
            $data->rubbish_fee = $request->input('rubbish_fee');
            $data->customer_status = $request->input('customer_status');
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
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
