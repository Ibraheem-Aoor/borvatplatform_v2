<?php

namespace App\Exports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShipmentExport implements FromCollection, WithHeadings, WithColumnWidths, WithMapping, WithStyles
{

    public $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Shipment::query()->whereIn('id'  , $this->ids)->with('order')->get();
    }


    public function headings(): array
    {
        return [
            'Order ID',
            'Shipment ID',
            'Place Date',
        ];
    }


    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                // Make the first row bold
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                // Center align the first row
            ],
            'A1:Z1000' => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }


    public function map($shipment): array
    {
        return [
            $shipment->order?->api_id,
            $shipment?->api_id,
            $shipment?->place_date,
        ];
    }



}
