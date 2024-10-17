<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class WasteBankController extends Controller
{
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
                <button onclick="editDataTPS3R(' . $data->waste_bank_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                <button onclick="deleteData(`' . route('waste-bank.destroy', $data->waste_bank_id) . '`)" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>
                <button onclick="createDataTonase(' . $data->waste_bank_id . ')" class="btn btn-sm custom-btn-sm btn-warning">Tambah Tonase</button>
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
        return view('admin-yrpw-new.manage-waste-bank.index', [
            'villages' => $villages
        ]);
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
                'waste_bank_name' => 'required',
                'village_id' => 'required',
            ],
            // Custom Error Messages
            [
                'waste_bank_name.required' => 'Nama TPS3R tidak boleh kosong',
                'village_id.required' => 'Desa tidak boleh kosong',
            ]
        );

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


    public function show($id)
    {
        $data = WasteBank::query()->find($id);
        return response()->json($data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'waste_bank_name_edit' => 'required',
        ], [
            'waste_bank_name_edit.required' => 'Nama TPS3R tidak boleh kosong'
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

    public function destroy($id)
    {
        $data = WasteBank::query()->find($id);
        $data->delete();
    }
}
