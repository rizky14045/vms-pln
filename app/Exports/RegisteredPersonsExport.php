<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegisteredPersonsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $registeredPersons;
    protected $isEmployee;

    public function __construct(Collection $registeredPersons, bool $isEmployee = true)
    {
        $this->registeredPersons = $registeredPersons;
        $this->isEmployee = $isEmployee;
    }

    public function collection()
    {
        return $this->registeredPersons;
    }

    public function headings(): array
    {
        if ($this->isEmployee) {
            return [
                'Email',
                'Nama',
                'Status',
                'Tanggal Daftar',
            ];
        }

        return [
            'Email',
            'Nama',
            'Company',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function map($registeredPerson): array
    {
        $row = [
            $registeredPerson->user->email ?? '-',
            $registeredPerson->user->name ?? '-',
        ];

        if (!$this->isEmployee) {
            $row[] = $registeredPerson->user->company ?? '-';
        }

        $row[] = $registeredPerson->status;
        $row[] = optional($registeredPerson->created_at)->format('Y-m-d H:i:s');

        return $row;
    }
}
