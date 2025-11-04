<?php

namespace App\Exports;

use App\Models\ProductViews as ModelsViews;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelViews implements FromQuery,WithHeadings,WithMultipleSheets
{
    public function headings():array{
        return [
            'maluotxem',
            'luotxem',
            'ngayxem',
            'sanpham_id'
        ];
    }
    use Exportable;

    public function __construct($fromday,$today)
    {
        $this->fromday = $fromday;
        $this->today = $today;
    }

    public function query()
    {
        return ModelsViews::query()->whereDate('viewssanpham_ngay_xem','>=', $this->fromday)
        ->whereDate('viewssanpham_ngay_xem','<=', $this->today);
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ExcelViews($this->fromday,$this->today);
        $sheets[] = new ExcelViewsProduct($this->fromday,$this->today);
        return $sheets;
    }
}
