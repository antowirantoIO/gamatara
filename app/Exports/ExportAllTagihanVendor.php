<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportAllTagihanVendor implements WithMultipleSheets
{
    protected $project;
    protected $request;
    public function __construct($project, $request)
    {
        $this->request = $request;
        $this->project = $project;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->project as $vendorId => $vendorProjects) {
            $sheetName = $vendorId;
            $sheets[$sheetName] = new ExportTagihanVendor($this->request, $vendorId,$vendorProjects->first()->id_vendor);
        }
        // dd($sheets);
        return $sheets;
    }
}
