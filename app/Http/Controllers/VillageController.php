<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class VillageController extends Controller
{
    public function data()
    {
        $listdata = Village::query()
            ->orderByDesc('created_at')
            ->get();

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataVillage(' . $data->village_id . ')" class="btn btn-sm btn-info custom-btn-sm">Edit</button>
                <button onclick="deleteData(`' . route('village.destroy', $data->village_id) . '`)" class="btn btn-sm btn-danger custom-btn-sm">Hapus</button>
            ';
            })
            ->make();
    }

    public function index()
    {
        return view('admin-yrpw-new.manage-village.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'village_name' => 'required|unique:villages,village_name',
            'village_code' => 'required|numeric'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new Village();
            $data->village_name = $request->input('village_name');
            $data->village_code = $request->input('village_code');
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
    }

    public function show($id)
    {
        $village = Village::query()->find($id);
        return response()->json($village);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'village_name' => 'required',
            'village_code' => 'required|numeric',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = Village::query()->find($id);
            $data->village_name = $request->input('village_name');
            $data->village_code = $request->input('village_code');
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Updated Data'
            ]);
        }
    }

    public function destroy($id)
    {
        $data = Village::query()->find($id);
        $data->delete();
    }
}
