<?php


class ExampleTest extends TestCase
{

    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

        $this->visit('/');
             // ->see('Laravel 5');
    }

    public function test_creation_of_commodity_category()
    {
        $form_data = [
            'category_name' => 'Carpentry Tools'
        ];

        $this->visit('commodity_categories/create')
             ->submitForm('Create', $form_data)
             ->seePageIs('commodity_categories');
    }

}
