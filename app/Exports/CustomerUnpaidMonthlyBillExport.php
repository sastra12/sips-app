<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerUnpaidMonthlyBillExport implements FromView
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function view(): View
    {
        return view('export-excel.export-customer-unpaid-monthly-bill-tps3r', [
            'customers' => $this->customers
        ]);
    }
}
