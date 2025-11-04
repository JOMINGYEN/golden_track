<?php

namespace App\Exports;

use App\Models\Product as ModelsProduct;
use App\Models\ProductViews as ModelsViews;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelViewsProduct implements FromQuery,WithHeadings
{
    public function headings():array{
        return [
            'sanpham_id',
            'sanpham',
            'giaban'
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
        $views=ModelsViews::query()->select('sanpham_id')->whereDate('viewssanpham_ngay_xem','>=', $this->fromday)
        ->whereDate('viewssanpham_ngay_xem','<=', $this->today);
        return ModelsProduct::query()->select('id','sanpham_ten','sanpham_gia_ban')->whereIn('id',$views);
    }
}
