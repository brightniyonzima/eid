<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStock_adjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('stock_adjustments', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('facility_id');
$table->integer('commodity_id');
$table->string('adjustment_type');
$table->integer('change_in_quantity');
$table->string('remarks');

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
        Schema::drop('stock_adjustments');
    }

}
