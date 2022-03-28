<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\Category;
use \App\Models\Product;
use \App\Models\ProductsCategory;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(100)->create();
        $categories = Category::factory()->count(20)->create();
        Product::factory()->count(1000)->create()->each(function (Product  $product) use ($users , $categories) {
            $product->update(['user_id' => $users->random()->id]);
            ProductsCategory::factory()->create([
                'product_id' => $product->id,
                'category_id' => $categories->random()->id
            ]);
        });
    }
}
