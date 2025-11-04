<?php

namespace App\Exports;

use App\Models\Order as ModelsOrder;
use App\Models\OrderDetail as ModelsOrderDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelOrderDetail implements FromQuery, WithHeadings
{
    use Exportable;

    protected $fromday;
    protected $today;

    public function __construct($fromday, $today)
    {
        $this->fromday = $fromday;
        $this->today = $today;
    }

    public function headings(): array
    {
        return [
            'chitietdonhang_id',
            'sanpham_id',
            'madonhang',
            'dongia',
            'soluong',
            'donhang_id'
        ];
    }

    public function query()
    {
        $order = ModelsOrder::query()
            ->select('id')
            ->whereDate('dondathang_ngay_dat_hang', '>=', $this->fromday)
            ->whereDate('dondathang_ngay_dat_hang', '<=', $this->today);

        return ModelsOrderDetail::query()
            ->whereIn('dondathang_id', $order)
            ->select([
                'id as chitietdonhang_id',
                'sanpham_id',
                'chitietdondathang_ma_don_dat_hang as madonhang',
                'chitietdondathang_don_gia as dongia',
                'chitietdondathang_so_luong as soluong',
                'dondathang_id as donhang_id'
            ]);
    }
}
