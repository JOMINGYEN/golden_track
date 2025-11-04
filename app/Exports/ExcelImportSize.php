<?php

namespace App\Exports;

use App\Models\Size as ModelsSize;
use App\Models\ProductImport as ModelsImport;
use App\Models\ProductImportDetail as ModelsImportDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelImportSize implements FromQuery,WithHeadings
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
        $order=ModelsImport::query()->select('id')->whereDate('donnhaphang_ngay_nhap','>=', $this->fromday)
        ->whereDate('donnhaphang_ngay_nhap','<=', $this->today);
        $order_detail= ModelsImportDetail::query()->select('size_id')->whereIn('donnhaphang_id',$order);
        return ModelsSize::query()->whereIn('id',$order_detail);
    }
}
