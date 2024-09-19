<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Validator;

class ManageCustomerByTPS3RController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $user_id = Auth::user()->id;
        $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name', 'waste_bank.waste_bank_users' => function ($query) {
                $query->select('id', 'name');
            }])
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
            ->make();
    }

    public function index()
    {
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-tps3r.manage-customer.index', [
            'customer_status' => $customer_status,
        ]);
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
            $data->waste_id = $waste_id[0]->waste_bank_id;
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
