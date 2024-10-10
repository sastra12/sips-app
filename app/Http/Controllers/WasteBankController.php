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
        $listdata = WasteBank::select("waste_bank_id", "waste_name", "village_id", "created_at")
            ->with(["village" => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc("created_at")
            ->get();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataTPS3R(' . $data->waste_bank_id . ')" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('waste-bank.destroy', $data->waste_bank_id) . '`)" class="btn btn-xs btn-danger">Hapus</button>
                <button onclick="createDataTonase(' . $data->waste_bank_id . ')" class="btn btn-xs btn-warning">Tambah Tonase</button>
            ';
            })
            ->addColumn('waste_bank_village', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    // data bank sampah yang belum di pegang admin user
    public function unassignedWasteBank()
    {
        $listdata = WasteBank::doesntHave('waste_bank_users')->get();
        return response()->json([
            'data' => $listdata
        ]);
    }

    public function index()
    {
        $villages = Village::doesntHave('waste_bank')->get();
        return view('admin-yrpw.manage-waste-bank.index', [
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
                'status' => 'Error',
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
            'waste_bank_name_edit' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = WasteBank::query()->find($id);
            $data->waste_name = $request->input('waste_bank_name_edit');
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
