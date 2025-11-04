<?php

namespace App\Exports;

use App\Models\Product as ModelsProduct;
use App\Models\ProductInStock as ModelsInStock;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelInStockProduct implements FromQuery,WithHeadings
{
    public function headings():array{
        return [
            'sanpham_id',
            'sanpham',
            'giaban'
        ];
    }

    use Exportable;

    public function query()
    {
        $in_stock=ModelsInStock::query()->select('sanpham_id');
        return ModelsProduct::query()->select('id','sanpham_ten','sanpham_gia_ban')->whereIn('id',$in_stock);
    }
}
