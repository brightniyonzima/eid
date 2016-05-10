<?php


class CommodityCrudTest extends TestCase /* CRUD = Create, Retrieve, Update, Delete */
{
    public function test_creation_of_commodity()// tests the C and the R in CRUD
    {
        return $this->create_commodity();
    }

    public function test_creation_of_commodity_category()// tests the C and the R in CRUD
    {
        return $this->create_commodity_category();
    }


    public function test_update_commodity_category( )// tests the U in CRUD
    {
        $category = $this->create_commodity_category();
        return $this->update_commodity_category( $category->id );
    }

    public function test_update_commodity( )// tests the U in CRUD
    {
        $commodity = $this->create_commodity();
        return $this->update_commodity( $commodity->id );
    }

    public function test_delete_commodity_category() // tests the D in CRUD
    {
        $category = $this->create_commodity_category();
        return $this->delete_commodity_category( $category );
    }

    public function test_delete_commodity() // tests the D in CRUD
    {
    // create a commodity
        $commodity = $this->create_commodity();
        return $this->delete_commodity( $commodity );
    }












/**^^^^^^^^^^^^^^^^^^^^^^^^^   UTILITY FUNCTIONS   ^^^^^^^^^^^^^^^^^^^^^^^^^**/

    protected function create_commodity_category()
    {

        $category = factory('EID\commodity_categories')->make();
        $form_data = $category->toArray();
        
    // create the category
        $this->visit('commodity_categories/create')
             ->submitForm('Create', $form_data)
             ->seePageIs('commodity_categories');

    // check that creation, above, succeeded:
        $this->visit('/commodity_categories')
             ->see($category->category_name)
             ->seeInDatabase('commodity_categories', ['id' => $category->id]);

        return $category;
    }


    protected function update_commodity_category( $category_id )
    {

    // change the category's name
        $new_category_name = 'some random-text: Yada Yada Yada';
        $new_data = ['category_name' => $new_category_name];

        $form_data = [
            "form_url" => "/commodity_categories/" . $category_id . "/edit",
            "success_url" => '/commodity_categories',
            "submit_button" => 'update_commodity',
            "data" => $new_data
        ];
        $verification_data = [
            'db_table' => 'commodity_categories',
            'text_to_see_on_page' => $new_category_name,

            'data' => $form_data["data"],
            'success_url' => $form_data["success_url"]
        ];

        $this->fill_and_submit_form($form_data, $verification_data);

        return $this;
    }


    protected function validate($form_data, $verify)
    {
        $this->assertTrue( array_key_exists("form_url", $form_data) );
        $this->assertTrue( array_key_exists("success_url", $form_data) );
        $this->assertTrue( array_key_exists("submit_button", $form_data) );
        $this->assertTrue( array_key_exists("data", $form_data) );

        if( $verify ){

            $this->assertTrue( array_key_exists("text_to_see_on_page", $verify) );
            $this->assertTrue( array_key_exists("db_table", $verify) );
        }
    }

    protected function store_form_data( $form_data )
    {
        $this->visit( $form_data["form_url"] )
             ->submitForm( $form_data["submit_button"], $form_data["data"] )
             ->seePageIs( $form_data["success_url"] );
    }

    protected function verify_stored_data( $form_data, $verify )
    {
        $do_not_verify = empty( $verify );
        
        if( $do_not_verify ) 
            return $this;


        $verify["db_data"] = array_key_exists("db_data", $verify) ? $verify["db_data"] : $form_data["data"]; 
        $verify["form_url"] = array_key_exists("form_url", $verify) ? $verify["form_url"] : $form_data["success_url"];


        $this->visit( $verify["form_url"] )
             ->see( $verify["text_to_see_on_page"] )
             ->seeInDatabase( $verify["db_table"], $verify["db_data"] );
    }


    protected function fill_and_submit_form($form_data, $verify = [])
    {
        $this->validate( $form_data, $verify );
        $this->store_form_data( $form_data );
        $this->verify_stored_data( $form_data, $verify );
        
        return $this;
    }


    protected function delete_db_row($db_table, $data_factory, $delete_url, $delete_button ='', $idField = 'id')
    {
        
        $db_row = $data_factory;
        if(is_string($data)){
            $db_row = factory( $data )->create();
        }

        $row_data = $db_row->toArray();
        $row_id = $row_data[ $idField ];
        $delete_button = $delete_button ?: "delete_" . $row_id;

    // delete the db_row
        $this->visit( $delete_url )
             ->seeInDatabase($db_table, $row_data)
             ->submitForm($delete_button, [])
             ->dontSeeInDatabase($db_table, $row_data);
    }


    protected function ignore_test_creation_of_commodity()// No longer needed
    {
        $category = $this->create_commodity_category();
        $commodity = factory('EID\commodities')->make( ['category_id' => $category->id] );
        $form_data = $commodity->toArray();


    // create the commodity
        $this->visit('/commodities/create')
             ->submitForm('store_commodity', $form_data)
             ->seePageIs('/commodities');


    // check that creation, above, succeeded
        $this->visit('/commodities')
             ->see($commodity->commodity_name)             
             ->seeInDatabase('commodities', $form_data);
             
    }

    protected function delete_record( $id )
    {
        return $this->submitForm("delete_" . $id, []);
    }

    protected function create_commodity()
    {
        $category = $this->create_commodity_category();
        $commodity = factory('EID\commodities')->make( ['category_id' => $category->id] );

        $form_data = [
            "form_url" => '/commodities/create',
            "success_url" => '/commodities',
            "submit_button" => 'store_commodity',
            "data" => $commodity->toArray()
        ];
        $verification_data = [
            'db_table' => 'commodities',
            'text_to_see_on_page' => $commodity->commodity_name,

        // these are optional (if not supplied, they'll be copied from $form_data automatically)
            'data' => $form_data["data"],
            'success_url' => $form_data["success_url"]
        ];

        $this->fill_and_submit_form($form_data, $verification_data);

        return $commodity;
    }


    protected function update_commodity( $commodity_id )
    {

    // change the commodity's name
        $new_commodity_name = 'some random-text: Blah Blah Blah';
        $new_data = ['commodity_name' => $new_commodity_name];

        $form_data = [
            "form_url" => "/commodities/" . $commodity_id . "/edit",
            "success_url" => '/commodities',
            "submit_button" => 'update_commodity',
            "data" => $new_data
        ];

        $verification_data = [
            'db_table' => 'commodities',
            'text_to_see_on_page' => $new_commodity_name,

            'data' => $form_data["data"],
            'success_url' => $form_data["success_url"]
        ];

        $this->fill_and_submit_form($form_data, $verification_data);

        return $this;    
    }



    protected function delete_commodity_category( $category )
    {
        // dd( $category );
        $category_data = $category->toArray();
        $id = $category->id;

    // delete the category
        $this->visit("/commodity_categories")
             ->seeInDatabase('commodity_categories', $category_data)
             ->delete_record($id)
             ->dontSeeInDatabase('commodity_categories', $category_data);

        return $this;
    }



    protected function delete_commodity( $commodity )
    {

        $commodity_data = $commodity->toArray();

    // delete the commodity
        $this->visit("/commodities")
             ->seeInDatabase('commodities', $commodity_data)
             ->delete_record($commodity->id)
             ->dontSeeInDatabase('commodities', $commodity_data);

        return $this;
    }

}