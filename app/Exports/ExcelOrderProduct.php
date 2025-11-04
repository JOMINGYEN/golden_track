<?php

namespace App\Exports;

use App\Models\Product as ModelsProduct;
use App\Models\Order as ModelsOrder;
use App\Models\OrderDetail as ModelsOrderDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelOrderProduct implements FromQuery,WithHeadings
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
        $order=ModelsOrder::query()->select('id')->whereDate('dondathang_ngay_dat_hang','>=', $this->fromday)
        ->whereDate('dondathang_ngay_dat_hang','<=', $this->today);
        $order_detail= ModelsOrderDetail::query()->select('sanpham_id')->whereIn('dondathang_id',$order);
        return ModelsProduct::query()->select('id','sanpham_ten','sanpham_gia_ban')->whereIn('id',$order_detail);
    }
}
