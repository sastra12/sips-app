<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function view(): View
    {
        return view('export-excel.export-customers', [
            'customers' => $this->customers
        ]);
    }
}
