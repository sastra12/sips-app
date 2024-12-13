<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Jobs\ImportCsvCustomersToDatabaseJob;
use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Http\Request;
use DataTables;
use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function wasteBankCustomerData()
    {
        $listdata = WasteBank::query()
            ->select("waste_bank_id", "waste_name", "village_id", "created_at")
            ->with(['village' => function ($query) {
                $query->select("village_id", "village_name");
            }])
            ->orderByDesc("created_at")
            ->get();

        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '<a href="' . route('customer-details.view', ['bankId' => $data->waste_bank_id]) . '" class="btn btn-sm custom-btn-sm btn-info">Customer Details</a>';
            })
            ->make();
    }

    public function index()
    {
        return view('admin-yrpw-new.manage-customer.index',);
    }

    public function viewCustomerDetails(Request $request)
    {
        $waste_id = WasteBank::query()->find($request->input('bankId'));
        if (!$waste_id) {
            abort(404);
        }
        $waste_banks = WasteBank::query()->get();
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-yrpw-new.manage-customer.waste-customer-details', [
            'customer_status' => $customer_status,
            'waste_banks' => $waste_banks
        ]);
    }

    public function customerData(Request $request)
    {
        $waste_id = $request->input('bankId');
        $listdata = Customer::query()
            ->where('waste_id', '=', $waste_id)
            ->orderByDesc('created_at')
            ->get();
        return Datatables::of($listdata)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                    <button onclick="editDataCustomer(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                    <button onclick="deleteData(`' . route('customer.destroy', $data->customer_id) . '`)" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>
                ';
            })
            ->make();
    }

    public function create()
    {
        //
    }

    public function store(CustomerRequest $request)
    {
        // dd($request->input('waste_id'));
        $validated = $request->safe();
        $data = new Customer();
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->waste_id = $validated['waste_id'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Added Data'
        ]);
    }

    public function uploadFileCustomer(Request $request)
    {
        try {
            $wasteId = $request->input('bankId');
            $validated = Validator::make(
                $request->all(),
                [
                    'file' => 'required|mimes:csv,txt',
                    'bankId' => 'required'
                ],
                [
                    'file.required' => 'File tidak boleh kosong',
                    'file.mimes' => 'File harus dalam format csv',
                    'bankId.required' => 'Id TPS tidak boleh kosong'
                ]
            );

            if ($validated->fails()) {
                return response()->json([
                    'status' => 'Error',
                    'errors' => $validated->messages()
                ]);
            }

            if ($request->hasFile('file')) {
                // nama file
                $fileName = $request->file('file')->getClientOriginalName();
                // path file
                $fileWithPath = public_path('uploads') . '/' . $fileName;
                // cek apakah nama file sudah ada di directory
                if (!file_exists($fileWithPath)) {
                    $request->file('file')->move(public_path('uploads'), $fileName);
                }
                // load file csv
                $records = array_map('str_getcsv', file($fileWithPath));
                $header = null;
                $dataFromCsv = [];
                foreach ($records as $record) {
                    if ($header == null) {
                        $header = $record;
                    } else {
                        $dataFromCsv[] = $record;
                    }
                }
                // pecah data dengan chunk
                $dataFromCsv = array_chunk($dataFromCsv, 500);
                $batch = Bus::batch([])->dispatch();

                // looping through each 1000/300 employess
                foreach ($dataFromCsv as $index => $dataCsv) {
                    // looping through each employess data
                    foreach ($dataCsv as $data) {
                        $employeeData[$index][] = array_combine($header, $data);
                    }
                    $batch->add(new ImportCsvCustomersToDatabaseJob($employeeData[$index], $wasteId));
                }
                session([
                    'batchId' => $batch->id,
                    'message' => 'Proses di lakukan di belakang',
                    'fileName' => $fileWithPath
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);
            dd($e);
        }
    }

    public function progress()
    {
        return view('progress.progress-yrpw');
    }

    public function batchStatus(Request $request)
    {
        // Ambil batch id dari session
        $batchId = $request->session()->get('batchId');
        $fileName = $request->session()->get('fileName');
        $batch = Bus::findBatch($batchId);
        if ($batch) {
            if ($batch->finished()) {
                $request->session()->forget('message');
                $request->session()->forget('batchId');
                unlink($fileName);
            }
            return response()->json([
                'total_jobs' => $batch->totalJobs,
                'processed_jobs' => $batch->processedJobs(),
                'progress' => $batch->progress(),
                'pending_jobs' => $batch->pendingJobs,
                'status' => $batch->finished() ? 'Selesai' : 'Sedang diproses'
            ]);
        }
    }

    public function show($id)
    {
        $customer = Customer::query()->find($id);
        return response()->json($customer);
    }

    public function edit($id)
    {
        //
    }

    public function update(CustomerRequest $request, $id)
    {
        $validated = $request->safe();
        $data = Customer::query()->find($id);
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->waste_id = $validated['waste_id'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = Customer::query()->find($id);
            $data->delete();
            return response()->json([
                'status' => 'Success',
                'message' => "Sukses menghapus data"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'False',
                'message' => 'Gagal menghapus data'
            ]);
        }
    }
}
