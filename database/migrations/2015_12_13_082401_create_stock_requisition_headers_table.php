<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockRequisitionHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('stock_requisition_headers', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('facility_id');
                $table->date('requisition_date');
                $table->string('requisition_method');
                $table->string('requestors_name');
                $table->string('requestors_phone');
                $table->string('requestors_batch_number');
                $table->integer('approved_by');
                $table->date('date_approved');
                $table->integer('dispatched_by');
                $table->date('date_dispatched');
                $table->string('receivers_name');
                $table->string('receivers_phone');
                $table->date('date_received');

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
        Schema::drop('stock_requisition_headers');
    }

}
