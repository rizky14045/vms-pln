<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VisitorCardHistoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $histories;

    public function __construct(Collection $histories)
    {
        $this->histories = $histories;
    }

    public function collection()
    {
        return $this->histories;
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Nomor Kartu',
            'Tanggal Mulai',
            'Jam Mulai',
            'Tanggal Selesai',
            'Jam Selesai',
        ];
    }

    public function map($history): array
    {
        return [
            $history->user->name ?? '-',
            $history->visitorCard->card_number ?? '-',
            optional($history->borrowed_at)->format('Y-m-d'),
            optional($history->borrowed_at)->format('H:i:s'),
            $history->returned_at ? $history->returned_at->format('Y-m-d') : '-',
            $history->returned_at ? $history->returned_at->format('H:i:s') : '-',
        ];
    }
}
