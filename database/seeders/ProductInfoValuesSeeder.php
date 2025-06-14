<?php

namespace Database\Seeders;

use App\Models\ProductInfoValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductInfoValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductInfoValue::updateTable();
    }
}
