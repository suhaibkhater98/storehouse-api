<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Product;
use \App\Models\Category;


class CreateProductsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class , 'product_id');
            $table->foreignIdFor(Category::class , 'category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_categories');
    }
}
