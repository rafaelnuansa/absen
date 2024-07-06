<?php 
namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping
{
    protected $buildingId;
    protected $positionId;
    protected $isActive;

    public function __construct($buildingId = null, $positionId = null, $isActive = null)
    {
        $this->buildingId = $buildingId;
        $this->positionId = $positionId;
        $this->isActive = $isActive;
    }

    public function collection()
    {
        $query = Employee::query()
            ->select('employees.id', 'employees.code', 'employees.name', 'employees.email', 'positions.name as position_name', 'buildings.name as building_name', 'employees.is_active')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('buildings', 'employees.building_id', '=', 'buildings.id');

        if ($this->buildingId) {
            $query->where('employees.building_id', $this->buildingId);
        }

        if ($this->positionId) {
            $query->where('employees.position_id', $this->positionId);
        }

        if ($this->isActive !== null) {
            $query->where('employees.is_active', $this->isActive);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['ID', 'Code', 'Name', 'Email', 'Position', 'Departement', 'Is Active'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID
            'B' => 30, // Code
            'C' => 30, // Name
            'D' => 25, // Email
            'E' => 30, // Position
            'F' => 30, // Building
            'G' => 10, // Is Active
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->code,
            $employee->name,
            $employee->email,
            $employee->position_name,
            $employee->building_name,
            $employee->is_active ? 'Active' : 'Inactive',
        ];
    }
}
