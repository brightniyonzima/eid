<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;


define('BATCH_ID', '22');// can be any positive number (NB: its a string)
define('DBS_SAMPLE_ID', '35');// can be any positive number (NB: its a string)

define('USE_WRONG_ORDER', 54);
define('USE_CORRECT_ORDER', 45);
define('MAKE_ALL_DATES_EQUAL', 55);

class BatchesTest extends TestCase
{
/*
 * NOTE:
 *  functions ending in '_dbs_data' or '_batch_data' expect or returns an array
 *  functions ending in '_dbs' or '_batch' expect or return a model
**/
 
    use HttpTrait;
    use WithoutMiddleware;

// ============================== SECTION 1: TESTS ===================================

    private $batch_data = ['batch_number' => '12345', 'envelope_number' => '56789'];


    public function test_NewBatch_ifBatchNumberExists_sayItExists()
    {
        $this->save_batch_to_database( $this->batch_data );// put batch into DB

        $reply = $this->check_if_batch_exists_in_database( $this->batch_data );
        $reply->seeJson(['already_exists' => true]);
    }


    public function test_NewBatch_ifBatchNumberDoesNotExist_sayItDoesNotExist()
    {
        $reply = $this->check_if_batch_exists_in_database( $this->batch_data );
        $reply->seeJson(['already_exists' => false]);
    }


    public function test_SaveExistingBatch_ifAllDataProvided_returnsBatchID()
    {
        $existing_batch = $this->get_existing_batch( BATCH_ID );
        $modified_batch = $this->modify_batch_data( $existing_batch->id );
        $http_response = $this->store_batch_data( $modified_batch );

        $http_response->seeJson(['batch_id' => BATCH_ID]);
    }

    public function test_ChangeDateOnExistingBatch_ifOrderIsCorrect_returnsBatchNumber()
    {
        $http_response = $this->change_batch_dates( USE_CORRECT_ORDER );
        $http_response->seeJson([ 'batch_number' => "{$http_response->original_batch_number}" ]);
    }


    public function test_ChangeDateOnExistingBatch_ifOrderIsWrong_returnsError()
    {
        $http_response = $this->change_batch_dates( USE_WRONG_ORDER );
        $http_response->seeJson()->see('error');
    }

    public function test_ChangeDateOnExistingBatch_ifOrderIsEqual_returnsBatchNumber()
    {

        $http_response = $this->change_batch_dates( MAKE_ALL_DATES_EQUAL );
        $http_response->seeJson([ 'batch_number' => "{$http_response->original_batch_number}" ]);
    }



    public function test_SaveNewBatch_ifAllDataProvided_returnsBatchID()
    {
        $new_batch = $this->generate_batch_data();
        $http_response = $this->store_batch_data( $new_batch );
        
        $http_response->seeJson()->see('batch_id');// expects JSON with a 'batch_id' field
    }



    public function test_SaveNewDBS_ifAllDataProvided_returnsRowID()
    {
        $new_dbs_sample = $this->generate_dbs_data( );
        $http_response = $this->store_dbs_data( $new_dbs_sample );

        $http_response->seeJson()->see('row_id')->see('row_number');// expects JSON with 'row_id' & 'row_number' fields
    }


    public function test_SaveExistingDBS_ifAllDataProvided_returnsRowID()
    {
        $dbs_sample = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $modified_dbs = $this->modify_dbs_data( $dbs_sample );

        $http_response = $this->store_dbs_data( $modified_dbs );
        $http_response->seeJson()->see('row_id')->see('row_number');// expects JSON with 'row_id' & 'row_number' fields
    }


// ============================== SECTION 1: TEST LOGIC ===================================



    public function change_batch_dates( $date_order )
    {
        $existing_batch = $this->get_existing_batch( BATCH_ID );
        $modified_batch = $this->modify_batch_date_data( $existing_batch , $date_order );

        $http_response = $this->store_batch_data( $modified_batch, '/bDate' );
        $http_response->original_batch_number = $modified_batch['batch_number'];

        return $http_response;
    }



    public function get_existing_dbs_data( $sample_id, $batch_id = BATCH_ID, $rowNumber = 1 )
    {
        // Create a batch, then attach the newly created sample to it.
        // We can now return the newly created sample to whoever needs an existing sample

        $batch = factory('EID\Models\Batch')->create( ['id' => $batch_id] );
        $sample = factory('EID\Models\Sample')->create( ['id' => $sample_id, 'batch_id' => $batch_id] );
        
        $dbs_data = $sample->toArray() + [  "batch_id"  => $batch_id, 
                                            "rowNumber" => $rowNumber, 
                                            "sample_$rowNumber" => $sample_id ];
        return $dbs_data;
    }

    public function modify_dbs_data( $original_dbs_sample )
    {
        // modify every field in the given sample - except its id:

        $sample_id = $original_dbs_sample[ ("sample_" . $original_dbs_sample["rowNumber"]) ];
        $batch_id = BATCH_ID+1;

        $new_data = $this->generate_dbs_data( $sample_id, $batch_id );
        $modified_data = array_merge( $original_dbs_sample, $new_data);

        return $modified_data;
    }


    public function generate_dbs_data( $sample_id = '', $batch_id = BATCH_ID, $rowNumber = 1 )
    {
        $batch = factory('EID\Models\Batch')->create( ['id' => $batch_id] );
        $dbs_fields = factory('EID\Models\Sample')->make( ['sample' => $sample_id] )->toArray();

        foreach ($dbs_fields as $key => $value) {
            $dbs_data[ $key . "_" . $rowNumber] = $value;
        }

        return $dbs_data + ['rowNumber' => $rowNumber, 'batch_id' => $batch_id ];
    }



    public function modify_batch_date_data( $old_batch_data , $date_order_to_test )
    {
        $batch_id = $old_batch_data->id;
        $batch_number = $old_batch_data->batch_number;

        $batch_data = $this->change_batch_dates_to( $batch_id, $date_order_to_test )->toArray();

        return [
            'date_dispatched_from_facility' =>  $batch_data['date_dispatched_from_facility'],
            'date_rcvd_by_cphl'             =>  $batch_data['date_rcvd_by_cphl'],

            'batch_number'                  =>  $batch_number,
            'batch_id'                      =>  $batch_id
        ];
    }



    public function change_batch_dates_to( $batch_id, $order_of_dates )
    {
        switch ($order_of_dates) {
            case MAKE_ALL_DATES_EQUAL: 
                    return $this->make_batch_with_equal_dates( $batch_id );
            
            case USE_CORRECT_ORDER: 
                    return $this->make_batch_with_correct_dates( $batch_id );
            
            case USE_WRONG_ORDER: 
                    return $this->make_batch_with_wrong_dates( $batch_id );
            
            default: throw new Exception("In change_batch_dates_to(): Unknown order_of_dates", 1);
        }
    }



// ========================= SECTION 3: WRAPPERS & UTILITIES =========================



    public function store_dbs_data( $dbs_data )
    {
        return $this->http_get('/dbs', $dbs_data);
    }


    public function generate_batch_data()
    {
        return factory('EID\Models\Batch')->make()->toArray();
    }


    public function get_existing_batch( $batch_id )
    {
        // create batch with given batch_id - so that we can be sure it exists:
        return factory('EID\Models\Batch')->create( ['id' => $batch_id] );
    }


    public function modify_batch_data( $batch_id , $order_of_dates = USE_CORRECT_ORDER )
    {
        // returns a batch where all the data is different - except batch_id:
        return factory('EID\Models\Batch')->make( ['id' => $batch_id ] )->toArray();
    }


    public function make_batch_with_correct_dates( $id )
    {        
        return factory('EID\Models\Batch')->make( ['id' => $id] );
    }


    public function make_batch_with_equal_dates( $id )
    {
        $today = date('Y-m-d');

        $seed_data = [ 'id' => $id,
                        'date_dispatched_from_facility' =>  $today,
                        'date_rcvd_by_cphl'             =>  $today,
                        'date_entered_in_DB'            =>  $today
                    ];
        
        return factory('EID\Models\Batch')->make( $seed_data );
    }

    public function make_batch_with_wrong_dates( $id )
    {
        $batch =  factory('EID\Models\Batch')->make( ['id' => $id] );
    
    // make the dates wrong (by swapping date_rcvd and date_dispatched)
        $tmp = $batch->date_dispatched_from_facility;
        $batch->date_dispatched_from_facility = $batch->date_rcvd_by_cphl;
        $batch->date_rcvd_by_cphl = $tmp;

        return $batch;
    }


    public function store_batch_data( $batch_data , $route = '/batch')
    {
        return $this->http_get($route, $batch_data);
    }


    public function save_batch_to_database( $batch_data = [] )
    {
        return factory('EID\Models\Batch')->create( $batch_data );
    }


    public function check_if_batch_exists_in_database( $batch_data )
    {
        return $this->http_get('/vBatchNo', $batch_data);
    }
}