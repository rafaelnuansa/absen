<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\Presence;
use App\Models\Position;
use App\Models\Building;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class EmployeeMonthlyAttendanceExport implements FromCollection, WithEvents
{
    protected $month;
    protected $year;
    protected $positionId;
    protected $buildingId;

    public function __construct(int $month, int $year, $positionId, $buildingId)
    {
        $this->month = $month;
        $this->year = $year;
        $this->positionId = $positionId;
        $this->buildingId = $buildingId;
    }

    public function collection(): Collection
    {
        $positions = Position::all();
        $buildings = Building::all();

        $employeesQuery = Employee::with('position', 'building');

        if ($this->positionId) {
            $employeesQuery->where('position_id', $this->positionId);
        }
        if ($this->buildingId) {
            $employeesQuery->where('building_id', $this->buildingId);
        }

        $employees = $employeesQuery->orderBy('name')->get();

        $daysInMonth = Carbon::createFromDate($this->year, $this->month)->daysInMonth;

        $attendances = collect();

        // Header Information
        $headerRow1 = collect([' ', 'Month : ', Carbon::create()->month($this->month)->format('F')]);
        $headerRow2 = collect(['', 'Year : ', $this->year]);
        $headerRow3 = collect(['', 'Position : ', $this->positionId ? $positions->where('id', $this->positionId)->first()->name : '-']);
        $headerRow4 = collect(['', 'Department : ', $this->buildingId ? $buildings->where('id', $this->buildingId)->first()->name : '-']);
        $breakRow = collect(['']);

        $attendances->push($breakRow);
        $attendances->push($breakRow);
        $attendances->push($headerRow1);
        $attendances->push($headerRow2);
        $attendances->push($headerRow3);
        $attendances->push($headerRow4);
        $attendances->push($breakRow);
        // Date Header Row
        $dateHeaderRow = collect(['', 'No', 'Name', 'Employee ID', 'Position',  'Department']);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateHeaderRow->push(str_pad($day, 2, '0', STR_PAD_LEFT));
        }
        $attendances->push($dateHeaderRow);

        // Employee Data Rows
        foreach ($employees as $index => $employee) {
            $attendanceRow = collect();
            $attendanceRow->push('');
            $attendanceRow->push($index + 1);
            $attendanceRow->push($employee->name);
            $attendanceRow->push($employee->code ?? '-');
            $attendanceRow->push($employee->position->name ?? '-');
            $attendanceRow->push($employee->building->name ?? '-');


            $totalAttendance = 0; // Total kehadiran untuk karyawan ini
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($this->year, $this->month, $day)->toDateString();
                $presence = Presence::where('employee_id', $employee->id)
                    ->whereDate('date', $date)
                    ->first();

                $status = $presence ? strtoupper($presence->status[0]) : 'A';
                $attendanceRow->push($status);

                // Hitung total kehadiran
                if ($status === 'P') {
                    $totalAttendance++;
                }
            }

            // Tambahkan kolom total kehadiran
            $attendanceRow->push($totalAttendance);


            $attendances->push($attendanceRow);
        }

        return $attendances;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();
                // Set border for all cells
                $sheet->getStyle("B9:{$highestColumn}{$highestRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Set alignment for all cells
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Set font and background for headers
                $sheet->getStyle("B9:{$highestColumn}9")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D3D3D3'],
                    ],
                ]);


                // Set column widths
                $sheet->getColumnDimension('B')->setWidth(10); // No
                $sheet->getColumnDimension('C')->setWidth(20); // Name
                $sheet->getColumnDimension('D')->setWidth(20); // Emp ID,
                $sheet->getColumnDimension('E')->setWidth(20); // Position,
                $sheet->getColumnDimension('F')->setWidth(20); // Departement,

                // Set width for date columns
                for ($col = 'H'; $col <= $highestColumn; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(5);
                }
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                // Conditional formatting for attendance statuses
                for ($row = 9; $row <= $highestRow; $row++) {
                    for ($col = 7; $col <= $highestColumnIndex; $col++) {  // 'G' is the 7th column
                        $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                        $cell = $sheet->getCell($cellCoordinate);
                        $status = $cell->getValue();
                        switch ($status) {
                            case 'P':
                                $sheet->getStyle($cellCoordinate)->applyFromArray([
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => '00FF00'],
                                    ],
                                ]);
                                break;
                            case 'O':
                                $sheet->getStyle($cellCoordinate)->applyFromArray([
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => 'FFFF00'],
                                    ],
                                    'font' => [
                                        'color' => ['rgb' => 'FFFFFF'],
                                    ]

                                ]);
                                break;
                            case 'S':
                                $sheet->getStyle($cellCoordinate)->applyFromArray([
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => '0000FF'],
                                    ],
                                    'font' => [
                                        'color' => ['rgb' => 'FFFFFF'],
                                    ]
                                ]);
                                break;
                            case 'A':
                                $sheet->getStyle($cellCoordinate)->applyFromArray([
                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => ['rgb' => 'FF0000'],
                                    ],
                                    'font' => [
                                        'color' => ['rgb' => 'FFFFFF'],
                                    ]
                                ]);
                                break;
                        }
                    }
                }
            },
        ];
    }
}
