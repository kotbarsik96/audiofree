<?php

namespace App\Models;

use App\Models\Product\ProductInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Str;

class ProductInfoValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value'
    ];

    public static function updateTable()
    {
        $now = DB::raw("NOW()");

        // Характеристики, уже существующие в таблице
        $storedInfo = ProductInfoValue::all();
        // Характеристики, которые будут удалены из-за того, что отсутствуют у всех товаров (изначально просто копия $storedInfo, из которого будут удаляться элементы при прохождении по товарам)
        $infoToDelete = $storedInfo->map(fn($i) => $i);
        // Характеристики, которые нужно будет добавить
        $infoToStore = collect();

        // Проход по каждому товару
        Product::lazy()->each(function (Product $product) use ($storedInfo, $infoToStore, &$infoToDelete, $now) {
            // Проход по каждой характеристике товара
            $product->info->each(function (ProductInfo $info) use ($storedInfo, $infoToStore, &$infoToDelete, $now) {
                // Поиск несохранённых характеристик (нет в таблице product_info_values)
                $isNotStored = !$storedInfo
                    ->first(
                        fn($sInfo) =>
                        $sInfo->name === $info->name
                        && $sInfo->value === $info->value
                    )
                    && !$infoToStore->first(
                        fn($sInfo) =>
                        $sInfo['name'] === $info->name
                        && $sInfo['value'] === $info->value
                    );

                // Характеристика используется - удалить её из массива $infoToDelete
                $infoToDelete = $infoToDelete->filter(
                    fn($fInfo) =>
                    $fInfo->name === $info->name && $fInfo->value === $info->value
                    ? false : true
                );

                // Сбор несохранённых характеристик
                if ($isNotStored) {
                    $infoToStore->push(
                        [
                            'name' => $info->name,
                            'value' => $info->value,
                            'slug' => $info->slug,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            });
        });

        // Сохранение характеристик в таблицу product_info_values
        ProductInfoValue::insert($infoToStore->toArray());

        // Удаление неиспользуемых характеристик
        $infoToDelete->each(
            fn($info) =>
            ProductInfoValue::where('name', $info->name)
                ->where('value', $info->value)
                ->delete()
        );
    }
}
