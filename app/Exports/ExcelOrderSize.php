<?php

namespace App\Exports;

use App\Models\Size as ModelsSize;
use App\Models\Order as ModelsOrder;
use App\Models\OrderDetail as ModelsOrderDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelOrderSize implements FromQuery,WithHeadings
{
    public function headings():array{
        return [
            'size_id',
            'size',
            'thutu',
            'trangthai'
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
        $order=ModelsOrder::query()->select('id')->whereDate('dondathang_ngay_dat_hang','>=', $this->fromday)
        ->whereDate('dondathang_ngay_dat_hang','<=', $this->today);
        $order_detail= ModelsOrderDetail::query()->select('size_id')->whereIn('dondathang_id',$order);
        return ModelsSize::query()->whereIn('id',$order_detail);
    }
}
