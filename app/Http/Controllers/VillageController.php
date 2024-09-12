<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $listdata = Village::query()->get();

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editForm(`' . route('village.update', $data->village_id) . '`)" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('village.destroy', $data->village_id) . '`)" class="btn btn-xs btn-danger">Delete</button>
            ';
            })
            ->make();
    }

    public function index()
    {
        return view('village.index');
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
            'village_name' => 'required',
            'village_code' => 'required|numeric'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $village = Village::query()->find($id);
        return response()->json($village);
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
            'village_name' => 'required',
            'village_code' => 'required|numeric',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed updated',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Village::query()->find($id);
        $data->delete();
    }
}
