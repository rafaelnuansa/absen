<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeePresenceExport implements FromCollection, WithHeadings
{
    protected $employee;
    protected $absenByDate;

    public function __construct(Employee $employee, $absenByDate, $year, $month)
    {
        $this->employee = $employee;
        $this->absenByDate = $absenByDate;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->absenByDate as $date => $absens) {
            foreach ($absens as $absenData) {
                $absen = $absenData['absensi'];
                $lateMinutes = $absenData['lateMinutes'];
                $earlyMinutes = $absenData['earlyMinutes'];

                $data[] = [
                    'Kode Karyawan' => $this->employee->code,
                    'Nama Karyawan' => $this->employee->name,
                    'Tanggal' => $date,
                    'Foto Masuk' => $this->getPhotoUrl($absen->picture_in),
                    'Jam Masuk' => $absen->time_in,
                    'Terlambat' => $lateMinutes,
                    'Foto Pulang' => $this->getPhotoUrl($absen->picture_out),
                    'Jam Pulang' => $absen->time_out ?? '-',
                    'Pulang Cepat' => $earlyMinutes,
                    'Status' => $absen->status,
                    'Latitude Masuk' => $absen->latitude_longitude_in ?? '#',
                    'Longitude Masuk' => $absen->latitude_longtitude_out,
                ];
            }
        }

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'Kode Karyawan',
            'Nama Karyawan',
            'Tanggal',
            'Foto Masuk',
            'Jam Masuk',
            'Terlambat',
            'Foto Pulang',
            'Jam Pulang',
            'Pulang Cepat',
            'Status',
            'Latitude Masuk',
            'Longitude Masuk',
        ];
    }

    protected function getPhotoUrl($path)
    {
        // Assuming the photos are stored in the public directory
        return asset($path);
    }
}
