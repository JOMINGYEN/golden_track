<?php

namespace App\Exports;

use App\Models\ProductImport as ModelsImport;
use App\Models\ProductImportDetail as ModelsImportDetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelImportDetail implements FromQuery, WithHeadings
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
            'chitietnhap_id',
            'soluongnhap',
            'gianhap',
            'sanpham_id',
            'donnhap_id',
            'madonnhap'
        ];
    }

    public function query()
    {
        $orders = ModelsImport::query()
            ->select('id')
            ->whereDate('donnhaphang_ngay_nhap', '>=', $this->fromday)
            ->whereDate('donnhaphang_ngay_nhap', '<=', $this->today);

        return ModelsImportDetail::query()
            ->whereIn('donnhaphang_id', $orders)
            ->select([
                'id as chitietnhap_id',
                'chitietnhap_so_luong_nhap as soluongnhap',
                'chitietnhap_gia_nhap as gianhap',
                'sanpham_id',
                'donnhaphang_id as donnhap_id',
                'chitietnhap_ma_don_nhap_hang as madonnhap',
            ]);
    }
}
