<?php

namespace App\Exports;

use App\Models\BorvatOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithFormatData;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMappedCells;
class BorvatOrderExport implements FromQuery , WithHeadings , WithFormatData  , WithColumnFormatting , WithMappedCells
{
    protected $ids;
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function headings(): array
    {
        return [
            'Referentie' ,
            'Naam (contactpersoon)',
            'Straat',
            'Huisnummer',
            'Postcode',
            'Plaats',
            'Landcode',
            'Telefoonnummer',
            'E-mailadres'
        ];
    }



    public function map($order): array
    {
        return [
            $order->code,
            $order->contact_person,
            $order->street,
            $order->house_no,
            $order->zip_code,
            $order->city,
            $order->country,
            $order->phone,
            $order->email,
        ];
    }

    public function mapping(): array
    {
        return [
            'Referentie' => function ($order) {
                return $order->code;
            },
            'Naam (contactpersoon)' => function ($order) {
                return $order->contact_person;
            },
            'Straat' => function ($order) {
                return $order->street;
            },
            'Huisnummer' => function ($order) {
                return $order->house_no;
            },
            'Postcode' => function ($order) {
                return $order->zip_code;
            },
            'Plaats' => function ($order) {
                return $order->city;
            },
            'Landcode' => function ($order) {
                return $order->country;
            },
            'Telefoonnummer' => function ($order) {
                return $order->phone;
            },
            'E-mailadres' => function ($order) {
                return $order->email;
            },
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => '@',
            'F' => '@',
            'G' => '@',
            'H' => '@',
            'I' => '@',
        ];
    }




    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return BorvatOrder::query()->whereIn('id' , $this->ids)
                    ->OrderBy('place_date')
                    ->select([
                        'code' ,
                        'contact_person',
                        'street',
                        'house_no',
                        'zip_code',
                        'city',
                        'country',
                        'phone',
                        'email'
                    ]);
    }
}
