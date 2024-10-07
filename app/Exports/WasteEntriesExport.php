<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WasteEntriesExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $waste_entries, $wasteOrganicTotal, $wasteAnorganicTotal, $wasteResidueTotal, $tonaseTotal, $wasteReductionTotal, $residueDisposeTotal;

    // Terima data dari controller
    public function __construct($waste_entries, $wasteOrganicTotal, $wasteAnorganicTotal, $wasteResidueTotal, $tonaseTotal, $wasteReductionTotal, $residueDisposeTotal)
    {
        $this->waste_entries = $waste_entries;
        $this->wasteOrganicTotal = $wasteOrganicTotal;
        $this->wasteAnorganicTotal = $wasteAnorganicTotal;
        $this->wasteResidueTotal = $wasteResidueTotal;
        $this->tonaseTotal = $tonaseTotal;
        $this->wasteReductionTotal = $wasteReductionTotal;
        $this->residueDisposeTotal = $residueDisposeTotal;
    }

    public function view(): View
    {
        return view('export-excel.export-by-month', [
            'waste_entries' => $this->waste_entries,
            'wasteOrganicTotal' => $this->wasteOrganicTotal,
            'wasteAnorganicTotal' => $this->wasteAnorganicTotal,
            'wasteResidueTotal' => $this->wasteResidueTotal,
            'tonaseTotal' => $this->tonaseTotal,
            'wasteReductionTotal' => $this->wasteReductionTotal,
            'residueDisposeTotal' => $this->residueDisposeTotal
        ]);
    }
}
