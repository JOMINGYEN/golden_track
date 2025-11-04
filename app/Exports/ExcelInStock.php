<?php

namespace App\Exports;

use App\Models\ProductInStock as ModelsInStock;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelInStock implements FromCollection, WithHeadings, WithMultipleSheets
{
    public function headings(): array
    {
        return [
            'matonkho',
            'soluongdaban',
            'soluongton',
            'sanpham_id'
        ];
    }

    public function collection()
    {
        return ModelsInStock::select([
            'id as matonkho',
            'sanphamtonkho_so_luong_da_ban as soluongdaban',
            'sanphamtonkho_so_luong_ton as soluongton',
            'sanpham_id'
        ])->get();
    }

    public function sheets(): array
    {
        return [
            new ExcelInStock(),
     
            new ExcelInStockProduct()
        ];
    }
}
