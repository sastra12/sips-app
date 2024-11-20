<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVillageRequest;
use App\Http\Requests\UpdateVillageRequest;
use App\Models\Village;
use DataTables;


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

    public function store(StoreVillageRequest $request)
    {
        $validated = $request->safe();
        $data = new Village();
        $data->village_name =  $validated['village_name'];
        $data->village_code = $validated['village_code'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Added Data'
        ]);
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

    public function update(UpdateVillageRequest $request, $id)
    {
        $validated = $request->safe();
        $data = Village::query()->find($id);
        $data->village_name = $validated['village_name'];
        $data->village_code = $validated['village_code'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = Village::query()->find($id);
            $data->delete();
            return response()->json([
                'status' => 'Success',
                'message' => 'Sukses menghapus data'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'False',
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
