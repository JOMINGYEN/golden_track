<?php

namespace App\Exports;

use App\Models\Size as ModelsSize;
use App\Models\ProductInStock as ModelsInStock;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ExcelInStockSize implements FromQuery,WithHeadings
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

    public function query()
    {
        $in_stock=ModelsInStock::query()->select('size_id');
        return ModelsSize::query()->whereIn('id',$in_stock);
    }
}
