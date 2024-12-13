<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerStoreTPS3RRequest;
use App\Models\Customer;
use App\Models\WasteBank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ImportCsvCustomersToDatabaseJob;

class ManageCustomerByTPS3RController extends Controller
{

    public function data()
    {
        $user_id = Auth::user()->id;
        $customers = Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name'])
            ->orderByDesc('created_at')
            ->get();
        // return response()->json($customers);
        return Datatables::of($customers)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return  '
                <button onclick="editDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-info">Edit</button>
                <button onclick="deleteDataCustomerByTPS3R(' . $data->customer_id . ')" class="btn btn-sm custom-btn-sm btn-danger">Hapus</button>  
            ';
            })
            ->make();
    }

    public function index()
    {
        $customer_status = ['Rumah Tangga', 'Non Rumah Tangga'];
        return view('admin-tps3r-new.manage-customer.index', [
            'customer_status' => $customer_status,
        ]);
    }

    public function uploadFileCustomer(Request $request)
    {
        try {
            // ambil bank sampah berdasarkan id user yang login
            $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
                $query->where('user_id', '=', Auth::user()->id);
            })
                ->select('waste_bank_id')
                ->get();

            $validated = Validator::make(
                $request->all(),
                [
                    'file' => 'required|mimes:csv,txt',
                ],
                [
                    'file.required' => 'File tidak boleh kosong',
                    'file.mimes' => 'File harus dalam format csv',
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
                    $batch->add(new ImportCsvCustomersToDatabaseJob($employeeData[$index], $waste_id[0]->waste_bank_id));
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
        return view('progress.progress-tps3r');
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


    public function create()
    {
        //
    }

    public function store(CustomerStoreTPS3RRequest $request)
    {
        // Dapatkan id waste_bank berdasarkan user yang login
        $waste_id = WasteBank::whereHas('waste_bank_users', function (Builder $query) {
            $query->where('user_id', '=', Auth::user()->id);
        })
            ->select('waste_bank_id')
            ->get();

        $validated = $request->validated();
        $data = new Customer();
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->waste_id = $waste_id[0]->waste_bank_id;
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Added Data'
        ]);
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

    public function update(CustomerStoreTPS3RRequest $request, $id)
    {
        $validated = $request->validated();
        $data = Customer::query()->find($id);
        $data->customer_name = $validated['customer_name'];
        $data->customer_address = $validated['customer_address'];
        $data->customer_neighborhood = $validated['customer_neighborhood'];
        $data->customer_community_association = $validated['customer_community_association'];
        $data->rubbish_fee = $validated['rubbish_fee'];
        $data->customer_status = $validated['customer_status'];
        $data->save();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Updated Data'
        ]);
    }

    public function destroy($id)
    {
        $data = Customer::query()->find($id);
        $data->delete();
    }

    public function exportCustomer()
    {
        return Excel::download(new CustomersExport($this->getCustomer()), 'Data Pelanggan.xlsx');
    }

    protected function getCustomer()
    {
        $customersData = [];
        $user_id = Auth::user()->id;
        Customer::whereHas('waste_bank', function (Builder $query) use ($user_id) {
            $query->whereHas('waste_bank_users', function (Builder $query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        })
            ->with(['waste_bank:waste_bank_id,waste_name'])
            ->orderByDesc('created_at')
            ->chunk(500, function ($customers) use (&$customersData) {
                foreach ($customers as $customer) {
                    $customersData[] = [
                        'customer_name' => $customer->customer_name,
                        'waste_name' => $customer->waste_bank->waste_name,
                        'customer_address' => $customer->customer_address,
                        'customer_neighborhood' => $customer->customer_neighborhood,
                        'customer_community_association' => $customer->customer_community_association,
                        'rubbish_fee' => $customer->rubbish_fee,
                        'customer_status' => $customer->customer_status,
                        'created_at' => $customer->created_at,
                    ];
                }
            });
        return $customersData;
    }
}
