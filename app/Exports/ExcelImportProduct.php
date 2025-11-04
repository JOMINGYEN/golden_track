<?php

namespace App\Exports;

use App\Models\Product as ModelsProduct;
use App\Models\ProductImport as ModelsImport;
use App\Models\ProductImportDetail as ModelsImportDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelImportProduct implements FromQuery,WithHeadings
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
        $order=ModelsImport::query()->select('id')->whereDate('donnhaphang_ngay_nhap','>=', $this->fromday)
        ->whereDate('donnhaphang_ngay_nhap','<=', $this->today);
        $order_detail= ModelsImportDetail::query()->select('sanpham_id')->whereIn('donnhaphang_id',$order);
        return ModelsProduct::query()->select('id','sanpham_ten','sanpham_gia_ban')->whereIn('id',$order_detail);
    }
}
