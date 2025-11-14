<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfferColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
             $table->boolean('is_offer')->default(0);
        $table->text('offer_description')->nullable();
        $table->integer('offer_quantity')->nullable();
        $table->decimal('offer_price', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
            'is_offer',
            'offer_description',
            'offer_quantity',
            'offer_price'
        ]);
        });
    }
}
