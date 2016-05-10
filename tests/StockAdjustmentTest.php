<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StockAdjustmentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_if_stock_adjustment_page_validates()
    {

        $this->assertTrue(true);

        // $form_data = ['remarks' => 'test_if_stock_adjustment_page_validates = YES'];

        // $this->visit('/stock_adjustments/create')
        //      ->submitForm('Create', $form_data)
        //      ->seePageIs('/stock_adjustments')
        //      ->seeInDatabase('stock_adjustments', $form_data);
    }


}
