<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
            Schema::create('commodities', function(Blueprint $table) {
                $table->increments('id');
                $table->string('commodity_name');
                $table->smallInteger('category_id');
                $table->smallInteger('tests_per_unit');

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
        Schema::drop('commodities');
    }

}
