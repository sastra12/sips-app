<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCsvCustomersToDatabaseJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $customersData, $wasteBankId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customersData, $wasteId)
    {
        $this->customersData = $customersData;
        $this->wasteBankId = $wasteId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->customersData as $data) {
            $employee = new Customer();
            $employee->customer_name = $data["Nama Pelanggan"];
            $employee->customer_address = $data["Alamat Pelanggan"];
            $employee->customer_neighborhood = $data["RT"];
            $employee->customer_community_association = $data["RW"];
            $employee->rubbish_fee = str_replace(',', '', $data["Jumlah Iuran"]);
            $employee->customer_status = $data["Status Pelanggan"];
            $employee->waste_id = $this->wasteBankId;
            $employee->save();
        }
    }
}
