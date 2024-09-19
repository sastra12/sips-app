<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Village;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        $listdata = Village::has('waste_bank')
            ->with('waste_bank')
            ->get();
        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a href="' . route('waste-cust-details', ['bankId' => $data->waste_bank->waste_bank_id]) . '" class="btn btn-xs btn-info">Customer Details</a>';
            })
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->make();
    }

    public function index()
    {
        return view('admin-yrpw.manage-customer.index',);
    }

    public function wasteCustomerDetails()
    {
        $waste_banks = WasteBank::query()->get();
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-yrpw.manage-customer.waste-customer-details', [
            'customer_status' => $customer_status,
            'waste_banks' => $waste_banks
        ]);
    }

    public function wasteCustData(Request $request)
    {
        $waste_id = $request->input('bankId');
        $listdata = Customer::query()->where('waste_id', '=', $waste_id)->get();
        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                    <button onclick="editDataCustomer(' . $data->customer_id . ')" class="btn btn-xs btn-info">Edit</button>
                    <button onclick="deleteData(`' . route('customer.destroy', $data->customer_id) . '`)" class="btn btn-xs btn-danger">Hapus</button>
                ';
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_neighborhood' => 'required|numeric',
            'customer_community_association' => 'required|numeric',
            'rubbish_fee' => 'required|numeric',
            'customer_status' => 'required',
            'waste_id' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::query()->find($id);
        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
                'status' => 'Failed updated',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Customer::query()->find($id);
        $data->delete();
    }
}
