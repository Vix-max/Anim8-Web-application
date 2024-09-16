<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_category');
            $table->integer('quantity');
            $table->decimal('buy_price', 8, 2);
            $table->decimal('sell_price', 8, 2);
            $table->text('product_description');
            $table->longText('product_image')->nullable(); // Use longText for base64 images
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
