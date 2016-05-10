<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockRequisitionLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('stock_requisition_line_items', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('requisition_header_id');
                $table->integer('commodity_id');
                $table->integer('quantity_requested');
                $table->integer('quantity_forecasted');
                $table->integer('quantity_approved');
                $table->string('approval_result');
                $table->string('approval_comment');
                $table->string('requisition_status');

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
        Schema::drop('stock_requisition_line_items');
    }

}
