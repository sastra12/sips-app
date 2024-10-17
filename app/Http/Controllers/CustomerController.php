<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
                return '<a href="' . route('customer-details.view', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm custom-btn-sm btn-info">Customer Details</a>';
            })
            ->addColumn('village_name', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    public function index()
    {
        return view('admin-yrpw-new.manage-customer.index',);
    }

    public function viewCustomerDetails()
    {
        $waste_banks = WasteBank::query()->get();
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-yrpw-new.manage-customer.waste-customer-details', [
            'customer_status' => $customer_status,
            'waste_banks' => $waste_banks
        ]);
    }

    public function customerData(Request $request)
    {
        $waste_id = $request->input('bankId');
        $listdata = Customer::query()
            ->where('waste_id', '=', $waste_id)
            ->orderByDesc('created_at')
            ->get();
        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                    <button onclick="editDataCustomer(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                    <button onclick="deleteData(`' . route('customer.destroy', $data->customer_id) . '`)" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>
                ';
            })
            ->make();
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
                'customer_name' => 'required',
                'customer_address' => 'required',
                'customer_neighborhood' => 'required|numeric',
                'customer_community_association' => 'required|numeric',
                'rubbish_fee' => 'required|numeric',
                'customer_status' => 'required',
                'waste_id' => 'required',
            ],
            // Custom Error Message
            [
                'customer_name.required' => 'Nama pelanggan tidak boleh kosong',
                'customer_address.required' => 'Alamat pelanggan tidak boleh kosong',
                'customer_neighborhood.required' => 'Data RT tidak boleh kosong',
                'customer_neighborhood.numeric' => 'Data RT harus berupa angka',
                'customer_community_association.required' => 'Data RW tidak boleh kosong',
                'customer_community_association.numeric' => 'Data RW harus berupa angka',
                'rubbish_fee.required' => 'Data iuran tidak boleh kosong',
                'rubbish_fee.numeric' => 'Data iuran harus berupa angka',
                'customer_status.required' => 'Data status tidak boleh kosong',
                'waste_id.required' => 'Data TPS3R tidak boleh kosong',
            ]
        );

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
            $data->waste_id = $request->input('waste_id');
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
        $validated = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required',
                'customer_address' => 'required',
                'customer_neighborhood' => 'required|numeric',
                'customer_community_association' => 'required|numeric',
                'rubbish_fee' => 'required|numeric',
                'customer_status' => 'required',
            ],
            // Custom Error Message
            [
                'customer_name.required' => 'Nama pelanggan tidak boleh kosong',
                'customer_address.required' => 'Alamat pelanggan tidak boleh kosong',
                'customer_neighborhood.required' => 'Data RT tidak boleh kosong',
                'customer_neighborhood.numeric' => 'Data RT harus berupa angka',
                'customer_community_association.required' => 'Data RW tidak boleh kosong',
                'customer_community_association.numeric' => 'Data RW harus berupa angka',
                'rubbish_fee.required' => 'Data iuran tidak boleh kosong',
                'rubbish_fee.numeric' => 'Data iuran harus berupa angka',
                'customer_status.required' => 'Data status tidak boleh kosong',
            ]

        );
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
}
