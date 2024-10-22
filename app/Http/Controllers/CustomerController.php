<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;

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

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->safe();
        $data = new Customer();
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->waste_id = $validated['waste_id'];
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

    public function update(UpdateCustomerRequest $request, $id)
    {
        $validated = $request->safe();
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
}
