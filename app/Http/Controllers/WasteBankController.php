<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class WasteBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $listdata = WasteBank::with('village')->get();

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editForm(`' . route('waste-bank.update', $data->waste_bank_id) . '`)" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('waste-bank.destroy', $data->waste_bank_id) . '`)" class="btn btn-xs btn-danger">Delete</button>
            ';
            })
            ->addColumn('waste_bank_village', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    public function index()
    {
        $villages = Village::doesntHave('waste_bank')->get();
        return view('waste-bank.index', [
            'villages' => $villages
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
        $validated = Validator::make($request->all(), [
            'waste_bank_name' => 'required',
            'village_id' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new WasteBank();
            $data->waste_name = $request->input('waste_bank_name');
            $data->village_id = $request->input('village_id');
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
        $data = WasteBank::query()->find($id);
        return response()->json($data);
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
            'waste_bank_name' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed Updated Data',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = WasteBank::query()->find($id);
            $data->waste_name = $request->input('waste_bank_name');
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
        $data = WasteBank::query()->find($id);
        $data->delete();
    }
}
