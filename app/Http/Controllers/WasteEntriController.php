<?php

namespace App\Http\Controllers;

use App\Models\WasteBank;
use App\Models\WasteEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class WasteEntriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data()
    {
        $listdata = WasteEntry::with('waste_bank')->get();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataVillage(' . $data->entry_id . ')" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData(`' . route('village.destroy', $data->entry_id) . '`)" class="btn btn-xs btn-danger">Hapus</button>
            ';
            })
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->make();
    }

    public function dataTonaseByAdminTPS3R()
    {
        $userId = Auth::user()->id;
        $wasteEntries = WasteEntry::whereHas('waste_bank', function (Builder $query) use ($userId) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        })
            ->with(['waste_bank', 'waste_bank.waste_bank_users'])
            ->get();

        return Datatables::of($wasteEntries)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataVillage()" class="btn btn-xs btn-info">Edit</button>
                <button onclick="deleteData()" class="btn btn-xs btn-danger">Hapus</button>
            ';
            })
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->addColumn('tanggal_input', function ($data) {
                return date('d F Y', strtotime($data->created_at));
            })
            ->make();
    }


    public function index()
    {
        return view('tonase.index');
    }

    public function userIndexTonase()
    {
        return view('user-tonase.index');
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
            'waste_organic' => 'required|numeric',
            'waste_anorganic' => 'required|numeric',
            'waste_residue' => 'required|numeric',
            'date_entri' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'Failed added',
                'errors' => $validated->messages()
            ]);
        } else {
            $data = new WasteEntry();
            $data->waste_organic = $request->input('waste_organic');
            $data->waste_anorganic = $request->input('waste_anorganic');
            $data->waste_residue = $request->input('waste_residue');
            $data->created_at = $request->input('date_entri');
            $data->waste_total = $request->input('waste_organic') + $request->input('waste_anorganic') + $request->input('waste_residue');
            $data->waste_id = $request->input('waste_bank_id');
            $data->user_id = Auth::user()->id;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
