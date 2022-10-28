<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->string('name', 90);
            $table->integer('quantity');
            $table->string('upc_ean_isbn', 100)->nullable();
            $table->date('exp_date')->nullable();
            $table->integer('cost_price');
            $table->integer('selling_price');
            $table->string('image', 255)->default('no-foto.png');
            $table->timestamps();
            $table->tinyInteger('status')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_attributes');
    }
}
