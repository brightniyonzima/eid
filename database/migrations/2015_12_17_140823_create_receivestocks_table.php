<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceivestocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('receivestocks', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('commodity_id');
$table->integer('qty_rcvd');
$table->string('batch_number');
$table->date('arrival_date');
$table->date('expiry_date');

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
        Schema::drop('receivestocks');
    }

}
