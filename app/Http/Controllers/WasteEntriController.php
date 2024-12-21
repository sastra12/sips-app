<?php

namespace App\Http\Controllers;

use App\Models\WasteBank;
use App\Models\WasteEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WasteEntriesExport;
use App\Http\Requests\UpdateWasteEntriRequest;
use App\Http\Requests\WasteEntriRequest;

class WasteEntriController extends Controller
{
    // Admin YRPW
    public function data()
    {
        $wasteBankData = WasteBank::query()
            ->select("waste_bank_id", "waste_name", "village_id", "created_at")
            ->with(['village' => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc('created_at');

        return Datatables::of($wasteBankData)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <a href="' . route('waste-entri-details.view', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm btn-info custom-btn-sm">Tonase Details</a>
            ';
            })
            ->make();
    }

    // Admin YRPW
    public function viewWasteEntriDetails()
    {
        return view('admin-yrpw-new.manage-tonase.waste-entri-details');
    }

    // Admin YRPW
    public function wasteEntriData(Request $request)
    {
        $waste_id = $request->input('bankId');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = WasteEntry::with(['waste_bank' => function ($query) {
            $query->select("waste_bank_id", "waste_name");
        }])
            ->where('waste_id', '=', $waste_id);

        // Jika ada filter tanggal 
        if ($start_date) {
            $query->where('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $query->where('created_at', '<=', $end_date);
        }

        $listdata = $query->orderByDesc('created_at');

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->addColumn('tanggal_input', function ($data) {
                return date('d F Y', strtotime($data->created_at));
            })
            ->addColumn('action', function ($data) {
                return  '
                    <button onclick="editDataTonaseYRPW(' . $data->entry_id . ')" class="btn btn-sm btn-info custom-btn-sm custom-btn-sm">Edit</button>
                    <button onclick="deleteDataTonaseYRPW(' . $data->entry_id . ')" class="btn btn-sm btn-danger custom-btn-sm custom-btn-sm">Hapus</button>
                ';
            })
            ->make();
    }

    // Admin TPS3R
    public function dataTonaseByAdminTPS3R(Request $request)
    {
        // ini masih ngebug
        $userId = Auth::user()->id;
        $wasteEntries = WasteEntry::whereHas('waste_bank', function (Builder $query) use ($userId) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name', 'waste_bank.waste_bank_users:id,name'])
            ->orderByDesc('created_at');

        // Jika ada request tanggal mulai dan tanggal berakhir
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if ($start_date) {
            $wasteEntries->where('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $wasteEntries->where('created_at', '<=', $end_date);
        }

        $listdata = $wasteEntries->get();

        // return response()->json($listdata);
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataTonaseByTPS3R(' . $data->entry_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                <button onclick="deleteDataTonaseByTPS3R(' . $data->entry_id . ')" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>
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


    public function wasteBankFacilitator()
    {
        $wasteBankData = WasteBank::query()
            ->select("waste_bank_id", "waste_name", "village_id", "created_at")
            ->with(['village' => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc("created_at")
            ->get();

        return Datatables::of($wasteBankData)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <a href="' . route('waste-entri-details-facilitator.view', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm custom-btn-sm btn-info">Tonase Details</a>
            ';
            })
            ->addColumn('village', function ($data) {
                return $data->village->village_name;
            })
            ->make();
    }

    public function wasteEntriDataOnFacilitator(Request $request)
    {
        $waste_id = $request->input('bankId');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = WasteEntry::with(['waste_bank' => function ($query) {
            $query->select("waste_bank_id", "waste_name");
        }])
            ->where('waste_id', '=', $waste_id);

        // Jika ada filter tanggal 
        if ($start_date) {
            $query->where('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $query->where('created_at', '<=', $end_date);
        }

        $listdata = $query->orderByDesc('created_at')->get();

        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('waste_name', function ($data) {
                return $data->waste_bank->waste_name;
            })
            ->addColumn('tanggal_input', function ($data) {
                return date('d F Y', strtotime($data->created_at));
            })
            ->make();
    }

    public function wasteEntriDetailsFacilitator()
    {
        return view('admin-fasilitator-new.tonase-data.waste-entri-details-facilitator');
    }

    // Admin YRPW Export Data By Month
    public function exportTonaseByYRPW(Request $request)
    {
        $wasteOrganicTotal = 0;
        $wasteAnorganicTotal = 0;
        $wasteResidueTotal = 0;
        $tonaseTotal = 0;
        $wasteReductionTotal = 0;
        $residueDisposeTotal = 0;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $waste_id = $request->input('waste_id');

        // Validation
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'start_date.required' => 'Tanggal awal harus diisi',
            'end_date.required' => 'Tanggal akhir harus diisi',
        ]);

        // Tampilkan display
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        // Lakukan query berdasarkan filter yang diterima
        $waste_entries = WasteEntry::with(['waste_bank' => function ($query) {
            $query->select('waste_bank_id', 'waste_name');
        }])
            ->where('waste_id', $waste_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderByDesc('created_at')
            ->get();

        // Cek apakah tanggal awal lebih besar dari tangal akhir
        if ($start_date > $end_date) {
            return redirect()->back()->with('failed', 'Maaf tanggal awal lebih besar dari tanggal akhir. Mohon periksa kembali.')->withInput();
        }
        // Cek apakah datanya null atau tidak
        elseif (count($waste_entries) == null) {
            return redirect()->back()->with('failed', 'Maaf Data Tonase Tidak Ada');
        } else {
            foreach ($waste_entries as $value) {
                $wasteOrganicTotal = $wasteOrganicTotal + $value->waste_organic;
                $wasteAnorganicTotal = $wasteAnorganicTotal + $value->waste_anorganic;
                $wasteResidueTotal = $wasteResidueTotal + $value->waste_residue;
                $wasteReductionTotal = $wasteReductionTotal + (($value->waste_organic + $value->waste_anorganic) / $value->waste_total) * 100;
                $residueDisposeTotal = $residueDisposeTotal + ($value->waste_residue / $value->waste_total) * 100;
            }
            $wasteReductionTotal = round($wasteReductionTotal / count($waste_entries));
            $residueDisposeTotal = round($residueDisposeTotal / count($waste_entries));
            $tonaseTotal = $tonaseTotal + $wasteOrganicTotal + $wasteAnorganicTotal + $wasteResidueTotal;

            // Kembalikan hasil dalam format Excel menggunakan view
            return Excel::download(new WasteEntriesExport($waste_entries, $wasteOrganicTotal, $wasteAnorganicTotal, $wasteResidueTotal, $tonaseTotal, $wasteReductionTotal, $residueDisposeTotal), 'Data Sampah ' . $waste_entries[0]->waste_bank->waste_name . '.xlsx');
        }
    }

    // Admin Facilitator
    public function exportTonaseByFacilitator(Request $request)
    {
        $wasteOrganicTotal = 0;
        $wasteAnorganicTotal = 0;
        $wasteResidueTotal = 0;
        $tonaseTotal = 0;
        $wasteReductionTotal = 0;
        $residueDisposeTotal = 0;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $waste_id = $request->input('waste_id');

        // Validation
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'start_date.required' => 'Tanggal awal harus diisi',
            'end_date.required' => 'Tanggal akhir harus diisi',
        ]);

        // Tampilkan display
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        // Lakukan query berdasarkan filter yang diterima
        $waste_entries = WasteEntry::with(['waste_bank' => function ($query) {
            $query->select('waste_bank_id', 'waste_name');
        }])
            ->where('waste_id', $waste_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderByDesc('created_at')
            ->get();

        // Cek apakah tanggal awal lebih besar dari tangal akhir
        if ($start_date > $end_date) {
            return redirect()->back()->with('failed', 'Maaf tanggal awal lebih besar dari tanggal akhir. Mohon periksa kembali.')->withInput();
        }
        // cek apakah datanya null 
        elseif (count($waste_entries) == null) {
            return redirect()->back()->with('failed', 'Maaf Data Tonase Tidak Ada');
        } else {
            foreach ($waste_entries as $value) {
                $wasteOrganicTotal = $wasteOrganicTotal + $value->waste_organic;
                $wasteAnorganicTotal = $wasteAnorganicTotal + $value->waste_anorganic;
                $wasteResidueTotal = $wasteResidueTotal + $value->waste_residue;
                $wasteReductionTotal = $wasteReductionTotal + (($value->waste_organic + $value->waste_anorganic) / $value->waste_total) * 100;
                $residueDisposeTotal = $residueDisposeTotal + ($value->waste_residue / $value->waste_total) * 100;
            }
            $wasteReductionTotal = round($wasteReductionTotal / count($waste_entries));
            $residueDisposeTotal = round($residueDisposeTotal / count($waste_entries));
            $tonaseTotal = $tonaseTotal + $wasteOrganicTotal + $wasteAnorganicTotal + $wasteResidueTotal;

            // Kembalikan hasil dalam format Excel menggunakan view
            return Excel::download(new WasteEntriesExport($waste_entries, $wasteOrganicTotal, $wasteAnorganicTotal, $wasteResidueTotal, $tonaseTotal, $wasteReductionTotal, $residueDisposeTotal), 'Data Sampah ' . $waste_entries[0]->waste_bank->waste_name . '.xlsx');
        }
    }

    // Admin TPS3R
    public function exportTonaseByTPS3R(Request $request)
    {
        $wasteOrganicTotal = 0;
        $wasteAnorganicTotal = 0;
        $wasteResidueTotal = 0;
        $tonaseTotal = 0;
        $wasteReductionTotal = 0;
        $residueDisposeTotal = 0;

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'start_date.required' => 'Tanggal awal harus diisi',
            'end_date.required' => 'Tanggal akhir harus diisi',
        ]);

        // Tampilkan display
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        // Lakukan query berdasarkan filter yang diterima
        $userId = Auth::user()->id;
        $waste_entries = WasteEntry::whereHas('waste_bank', function (Builder $query) use ($userId) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name', 'waste_bank.waste_bank_users:id,name'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderByDesc('created_at')
            ->get();

        // Cek apakah tanggal awal lebih besar dari tangal akhir
        if ($start_date > $end_date) {
            return redirect()->back()->with('failed', 'Maaf tanggal awal lebih besar dari tanggal akhir. Mohon periksa kembali.')->withInput();
        }
        // Cek apakah datanya null atau tidak
        elseif (count($waste_entries) == null) {
            return redirect()->back()->with('failed', 'Maaf Data Tonase Tidak Ada');
        } else {
            foreach ($waste_entries as $value) {
                $wasteOrganicTotal = $wasteOrganicTotal + $value->waste_organic;
                $wasteAnorganicTotal = $wasteAnorganicTotal + $value->waste_anorganic;
                $wasteResidueTotal = $wasteResidueTotal + $value->waste_residue;
                $wasteReductionTotal = $wasteReductionTotal + (($value->waste_organic + $value->waste_anorganic) / $value->waste_total) * 100;
                $residueDisposeTotal = $residueDisposeTotal + ($value->waste_residue / $value->waste_total) * 100;
            }
            $wasteReductionTotal = round($wasteReductionTotal / count($waste_entries));
            $residueDisposeTotal = round($residueDisposeTotal / count($waste_entries));
            $tonaseTotal = $tonaseTotal + $wasteOrganicTotal + $wasteAnorganicTotal + $wasteResidueTotal;

            // Kembalikan hasil dalam format Excel menggunakan view
            return Excel::download(new WasteEntriesExport($waste_entries, $wasteOrganicTotal, $wasteAnorganicTotal, $wasteResidueTotal, $tonaseTotal, $wasteReductionTotal, $residueDisposeTotal), 'Data Sampah ' . $waste_entries[0]->waste_bank->waste_name . '.xlsx');
        }
    }

    // Admin YRPW
    public function index()
    {
        return view('admin-yrpw-new.manage-tonase.index');
    }

    // Admin TPS3R
    public function userIndexTonase()
    {
        return view('admin-tps3r-new.manage-tonase.index');
    }

    // Admin Tonase
    public function viewWasteBankFacilitator()
    {
        return view('admin-fasilitator-new.tonase-data.index');
    }

    public function create()
    {
        //
    }

    public function store(WasteEntriRequest $request)
    {
        $validated = $request->validated();

        $data = WasteEntry::where('created_at', '=', $request->input('date_entri'))
            ->where('waste_id', '=', $request->input('waste_bank_id'))
            ->exists();
        // Cek apakah data tonase dengan tanggal sekian sudah di input
        if ($data) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tonase pada tanggal ini sudah di inputkan'
            ]);
        } else {
            $data = new WasteEntry();
            $data->waste_organic = $validated['waste_organic'];
            $data->waste_anorganic = $validated['waste_anorganic'];
            $data->waste_residue = $validated['waste_residue'];
            $data->created_at = $validated['date_entri'];
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

    // Admin TPS3R
    public function userTPS3RStore(WasteEntriRequest $request)
    {
        $validated = $request->validated();
        // Dapatkan id waste_bank berdasarkan user yang login
        $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', '=', Auth::user()->id);
        })->pluck('waste_bank_id');

        $data = WasteEntry::where('created_at', '=', $request->input('date_entri'))
            ->where('waste_id', '=', $waste_id[0])
            ->exists();

        // Cek apakah data tonase dengan tanggal sekian sudah di input
        if ($data) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tonase pada tanggal ini sudah di inputkan'
            ]);
        } else {
            $data = new WasteEntry();
            $data->waste_organic = $validated['waste_organic'];
            $data->waste_anorganic = $validated['waste_anorganic'];
            $data->waste_residue = $validated['waste_residue'];
            $data->created_at = $validated['date_entri'];
            $data->waste_total = $request->input('waste_organic') + $request->input('waste_anorganic') + $request->input('waste_residue');
            $data->waste_id = $waste_id[0];
            $data->user_id = Auth::user()->id;
            $data->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Added Data'
            ]);
        }
    }

    // Admin YRPW
    public function show($id)
    {
        $data = WasteEntry::query()->find($id);
        return response()->json($data);
    }

    // Admin TPS3R
    public function userTPS3RShow($id)
    {
        $data = WasteEntry::query()->find($id);
        return response()->json($data);
    }

    public function edit($id)
    {
        //
    }

    // Admin YRPW
    public function update(UpdateWasteEntriRequest $request, $id)
    {
        $validated = $request->validated();
        $data = WasteEntry::query()->find($id);
        $data->waste_organic = $validated['waste_organic'];
        $data->waste_anorganic = $validated['waste_anorganic'];
        $data->waste_residue = $validated['waste_residue'];
        // $data->created_at = $validated['date_entri'];
        $data->waste_total = $request->input('waste_organic') + $request->input('waste_anorganic') + $request->input('waste_residue');
        $data->user_id = Auth::user()->id;
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    // Admin TPS3R
    public function userTPS3RUpdate(UpdateWasteEntriRequest $request, $id)
    {
        // dd($request);
        $validated = $request->validated();
        $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', '=', Auth::user()->id);
        })->pluck('waste_bank_id');

        $data = WasteEntry::query()->find($id);
        $data->waste_organic = $validated['waste_organic'];
        $data->waste_anorganic = $validated['waste_anorganic'];
        $data->waste_residue = $validated['waste_residue'];
        // $data->created_at = $validated['date_entri'];
        $data->waste_total = $request->input('waste_organic') + $request->input('waste_anorganic') + $request->input('waste_residue');
        $data->waste_id = $waste_id[0];
        $data->user_id = Auth::user()->id;
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    // Admin YRPW
    public function destroy($id)
    {
        $data = WasteEntry::query()->find($id);
        $data->delete();
    }

    // Admin TPS3R
    public function userTPS3RDestroy($id)
    {
        $data = WasteEntry::query()->find($id);
        $data->delete();
    }
}
