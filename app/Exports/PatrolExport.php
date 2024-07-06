<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class PatrolExport implements FromCollection, WithMultipleSheets
{
    use Exportable;
    protected $month;
    protected $year;
    protected $buildingId;
    protected $shiftId;

    public function __construct($month, $year, $buildingId, $shiftId)
    {
        $this->month = $month;
        $this->year = $year;
        $this->buildingId = $buildingId;
        $this->shiftId = $shiftId;
    }

    public function sheets(): array
    {
        $sheets = [];

        $startDate = Carbon::createFromDate($this->year, $this->month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate <= $endDate) {
            $sheets[] = new PatrolSheet($startDate->copy(), $this->buildingId, $this->shiftId);
            $startDate->addDay();
        }

        return $sheets;
    }

    public function collection()
    {
        return collect([]);
    }

}
