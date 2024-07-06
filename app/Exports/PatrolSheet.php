<?php

namespace App\Exports;

use App\Models\Building;
use App\Models\Checkpoint;
use App\Models\Employee;
use App\Models\Patrol;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;

class PatrolSheet implements FromCollection, WithEvents, WithTitle
{
    protected $date;
    protected $buildingId;
    protected $shiftId;

    public function __construct($date, $buildingId, $shiftId)
    {
        $this->date = $date;
        $this->buildingId = $buildingId;
        $this->shiftId = $shiftId;
    }

    public function collection()
    {
        // Get all employees
        $employeesQuery = Employee::with(['building']);

        if ($this->shiftId) {
            $employeesQuery->where('shift_id', $this->shiftId);
        }

        // dd($this->buildingId);
         // Check if building_id is present in the request and apply filter
        if ($this->buildingId) {
            $employeesQuery->where('building_id', $this->buildingId);
        }

        $employees = $employeesQuery->orderBy('name')->get();

        // Initialize the collection
        $collection = collect([]);

        // Add header row
        $headerRow = ['No', 'Employee', 'Checkpoint', 'Time', 'Photos', 'Information', 'Status'];
        $collection->push($headerRow);

        // Add data for each employee
        $rowNumber = 1;
        foreach ($employees as $employee) {
            // Initialize data for current employee
            $rowData = [$rowNumber, $employee->name, '', '', '', '', ''];

            // If there are patrols for the employee on the specified date, add them to the data
            $patrol = Patrol::with(['checkpoint', 'photos'])
                ->where('employee_id', $employee->id)
                ->whereDate('date', $this->date->format('Y-m-d'))
                ->first();

            if ($patrol) {
                $rowData[2] = $patrol->checkpoint->name;
                $rowData[3] = $patrol->time;
                $photoPaths = $patrol->photos->pluck('file_path')->toArray();
                $photoUrls = array_map(function ($path) {
                    return asset($path);
                }, $photoPaths);
                $rowData[4] = implode(', ', $photoUrls);
                $rowData[5] = $patrol->information;
                $rowData[6] = $patrol->status;
            }

            // Add employee data to collection
            $collection->push($rowData);

            $rowNumber++;
        }

        return $collection;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style settings
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(40);
                $sheet->getColumnDimension('F')->setWidth(40);
                $sheet->getColumnDimension('G')->setWidth(15);

                // Insert photos
                $rows = $sheet->toArray();
                foreach ($rows as $rowIndex => $row) {
                    if ($rowIndex === 0) {
                        continue;
                    }
                    $photoPaths = explode(', ', $row[4]);
                    $currentColumn = chr(ord('A') + 4);
                    foreach ($photoPaths as $photoIndex => $photoPath) {
                        $fullPhotoPath = public_path($photoPath);
                        if (file_exists($fullPhotoPath) && !is_dir($fullPhotoPath)) {
                            $drawing = new Drawing();
                            $drawing->setName('Photo');
                            $drawing->setDescription('Patrol Photo');
                            $drawing->setPath($fullPhotoPath);
                            $drawing->setHeight(40);
                            $drawing->setCoordinates($currentColumn . ($rowIndex + 1));
                            $drawing->setWorksheet($sheet);
                            $currentColumn++;
                        }
                    }
                }
            },
        ];
    }

    public function title(): string
    {
        // Format the date to display only the day
        return $this->date->format('j');
    }

   
}
