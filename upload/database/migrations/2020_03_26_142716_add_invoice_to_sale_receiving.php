<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceToSaleReceiving extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('invoice_no');
        });
        Schema::table('receivings', function (Blueprint $table) {
            $table->string('invoice_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
        });
        Schema::table('receivings', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
        });
    }
}
