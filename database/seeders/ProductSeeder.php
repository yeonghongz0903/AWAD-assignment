<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Chiikawa Lip Cream with Mascot (Chiikawa)',
                'description' => 'Moisturizing lip cream featuring the adorable Chiikawa character. Perfect for keeping your lips soft while showing off your love for Chiikawa. Comes with a cute Chiikawa mascot charm.',
                'price' => 15.99,
                'stock' => 100,
                'image' => 'products/chiikawa-lip-cream.png',
            ],
            [
                'name' => 'Chiikawa Sugiri - Chiikawa Mascot',
                'description' => 'Official Chiikawa Sugiri plush mascot. Made with soft, high-quality materials, this cuddly companion is perfect for fans of all ages. Features the iconic Chiikawa design with attention to detail.',
                'price' => 24.99,
                'stock' => 50,
                'image' => 'products/chiikawa-sugiri.png',
            ],
            [
                'name' => 'Chiikawa Wash Towel',
                'description' => 'Soft and absorbent wash towel featuring the cute Chiikawa design. Perfect for daily use, made from high-quality cotton. The adorable Chiikawa pattern will brighten up your bathroom routine.',
                'price' => 12.99,
                'stock' => 75,
                'image' => 'products/chiikawa-towel.png',
            ],
            [
                'name' => 'Chiikawa Notebook',
                'description' => 'Cute Chiikawa-themed notebook with high-quality paper. Perfect for taking notes, journaling, or sketching. Features Chiikawa characters on the cover and throughout the pages.',
                'price' => 8.99,
                'stock' => 120,
                'image' => 'products/chiikawa-notebook.png',
            ],
            [
                'name' => 'Chiikawa Sugiri - Usagi Mascot',
                'description' => 'Adorable Usagi (Rabbit) version of the Chiikawa Sugiri mascot. Made with the same high-quality materials as the original, this cute bunny version is a must-have for Chiikawa collectors.',
                'price' => 24.99,
                'stock' => 40,
                'image' => 'products/chiikawa-usagi.png',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
