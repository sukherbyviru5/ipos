<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionItem;

class TransactionItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['transaction_id' => 1, 'product_id' => 1, 'qty' => 1, 'price' => 3500000, 'subtotal' => 3500000],
            ['transaction_id' => 2, 'product_id' => 2, 'qty' => 1, 'price' => 7500000, 'subtotal' => 7500000],
            ['transaction_id' => 2, 'product_id' => 4, 'qty' => 10, 'price' => 5000, 'subtotal' => 50000],
        ];

        foreach ($items as $item) {
            TransactionItem::create($item);
        }
    }
}
