<?php 

define('BATCH_ID', '22');// can be any positive number (NB: its a string)
define('DBS_SAMPLE_ID', '35');// can be any positive number (NB: its a string)

define('USE_WRONG_ORDER', 54);
define('USE_CORRECT_ORDER', 45);
define('MAKE_ALL_DATES_EQUAL', 55);


define('LESS_THAN_3', 2);
define('MAX_SAMPLES_IN_A_BATCH', 7);

define('NON_EXISTENT_SAMPLE', '0');
define('YES', 'YES');
define('NO', 'NO');

trait DbsTrait
{


//
// Note:
//  This Trait is merely the first step in refactoring this code.
//  These functions are repeated in other Test classes, which is wrong.
//  Those classes should use this Trait
//
//
// ============================== SECTION 1: UTILITIES ===============================


    public function change_batch_dates( $date_order )
    {
        $existing_batch = $this->get_existing_batch( BATCH_ID );
        $modified_batch = $this->modify_batch_date_data( $existing_batch , $date_order );

        $http_response = $this->store_batch_data( $modified_batch, '/bDate' );
        $http_response->original_batch_number = $modified_batch['batch_number'];

        return $http_response;
    }

    public function createSamplesForEidWorksheet()
    {
        $batch_id = BATCH_ID;
        $sample_id = DBS_SAMPLE_ID;

        for ($i=0; $i < 4; $i++) { 

            $batch_id = $batch_id + $i;
            $sample_id = $sample_id + ($i * MAX_SAMPLES_IN_A_BATCH);
            $dbs_data = $this->get_existing_dbs_data( $sample_id, $batch_id, MAX_SAMPLES_IN_A_BATCH );
        }
    }

    public function get_existing_dbs_data( $sample_id, $batch_id = BATCH_ID, $nRows = 1 )
    {
    // Creates a batch, then creates $nRows samples and adds them to the batch.
    // It then returns the last-created sample to whoever needs an existing sample

        if($nRows > MAX_SAMPLES_IN_A_BATCH)
            throw new Exception("get_existing_dbs_data(). Too many samples in batch", 1);
        

        if($nRows < 0){ 
        // This is a sickle cell worksheet, so it has more samples than MAX_SAMPLES_IN_A_BATCH.
        // It sneaks past if-clause above by being negative.
            $nRows = $nRows * -1;
        }

        // create the batch and it's samples:
        $batch = factory('EID\Models\Batch')->create( ['id' => $batch_id] );
        for ($i=0; $i < $nRows; $i++) { 
            $new_sample_id = $sample_id + $i;
            $new_sample_data = ['id' => $new_sample_id, 
                                'batch_id' => $batch_id, 
                                'pos_in_batch' => $i+1 ];
            $sample = factory('EID\Models\Sample')->create( $new_sample_data );
        }
        
        // make sure the row we shall use for testing is in the database:
        $this->seeInDatabase('dbs_samples', ['id' => $sample_id, 'batch_id' => $batch_id]);
   
        $rowNumber = $nRows;// return the last sample
        $dbs_data = $sample->toArray() + [  "batch_id"  => $batch_id, 
                                            "rowNumber" => $rowNumber, 
                                            "sample_$rowNumber" => $sample_id ];
        return $dbs_data;
    }

    public function modify_dbs_row($row_id, $new_data)
    {
        EID\Models\Sample::where('id', $row_id)->update( $new_data );
        
        $new_data += ['id' => $row_id];
        
        $this->seeInDatabase('dbs_samples', $new_data, null, 'modify_dbs_row() failed');
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


    public function ready_for_SCD_test( $ready_for_testing )
    {
        $scd_data = [];
        $scd_data['ready_for_SCD_test'] = $ready_for_testing;

        $scd_data['SCD_test_requested'] = YES;
        $scd_data['sample_rejected'] = YES;
        $scd_data['all_rejected'] = NO;
        $scd_data['reason_other'] = '';

        $batch = EID\Models\Batch::find( BATCH_ID );
        $scd_data['batch_number'] = $batch->batch_number;   
        $scd_data['tests_requested'] = $batch->tests_requested;
        
        return $scd_data;
    }


    public function reject_for_SCD()
    {    
        $pcr_data = [];


        $pcr_data['SCD_test_requested'] = YES;
        $pcr_data['ready_for_SCD_test'] = NO;
        $pcr_data['sample_rejected'] = YES;
        $pcr_data['all_rejected'] = NO;
        $pcr_data['reason_other'] = '';

        $batch = EID\Models\Batch::find( BATCH_ID );
        $pcr_data['batch_number'] = $batch->batch_number;   
        $pcr_data['tests_requested'] = $batch->tests_requested;
        
        return $pcr_data;
    }


    public function reject_all_for_SCD()
    {    
        $pcr_data = $this->reject_for_SCD();
        $pcr_data['all_rejected'] = YES;
        
        return $pcr_data;
    }

    public function remove_batch_data( $data )
    {
        $dbs_data = $data;
        unset($dbs_data['batch_number']);
        unset($dbs_data['tests_requested']);

        return $dbs_data;
    }

    public function remove_rejection_data( $data )
    {
        $dbs_data = $data;
        unset( $dbs_data['all_rejected'] );
        unset( $dbs_data['reason_other'] );

        return $dbs_data;
    }

    public function accept_all_for_SCD()
    {
        $all_data = $this->accept_for_SCD();
        $all_data = $this->remove_rejection_data( $all_data );
        $scd_data = $this->remove_batch_data( $all_data );

        DB::table('dbs_samples')->update( $scd_data );
    }


    public function accept_for_SCD()
    {
        $pcr_data = [];

        $pcr_data['SCD_test_requested'] = YES;
        $pcr_data['ready_for_SCD_test'] = NO;
        $pcr_data['sample_rejected'] = NO;
        $pcr_data['all_rejected'] = NO;
        $pcr_data['reason_other'] = '';

        $batch = EID\Models\Batch::find( BATCH_ID );
        $pcr_data['batch_number'] = $batch->batch_number;   
        $pcr_data['tests_requested'] = $batch->tests_requested;
        
        return $pcr_data;    
    }



    public function accept_for_PCR()
    {
        $pcr_data = [];

        $pcr_data['PCR_test_requested'] = YES;
        $pcr_data['sample_rejected'] = NO;
        $pcr_data['all_rejected'] = NO;
        $pcr_data['reason_other'] = '';

        $batch = EID\Models\Batch::find( BATCH_ID );
        $pcr_data['batch_number'] = $batch->batch_number;   
        $pcr_data['tests_requested'] = $batch->tests_requested;
        
        return $pcr_data;
    }

    public function reject_for_PCR()
    {    
        $pcr_data = [];

        $pcr_data['PCR_test_requested'] = YES;
        $pcr_data['sample_rejected'] = YES;
        $pcr_data['all_rejected'] = NO;
        $pcr_data['reason_other'] = '';

        $batch = EID\Models\Batch::find( BATCH_ID );
        $pcr_data['batch_number'] = $batch->batch_number;   
        $pcr_data['tests_requested'] = $batch->tests_requested;
        
        return $pcr_data;
    }

    public function reject_all_for_PCR()
    {    
        $pcr_data = $this->reject_for_PCR();
        $pcr_data['all_rejected'] = YES;
        
        return $pcr_data;
    }


    /**
     * Assert that a given where condition exists in the database.
     *
     *  Copied from Illuminate\Foundation\Testing\ApplicationTrait.php
     *  (Only modification is the addition of $err_message param)
     *
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function findInDatabase($table, array $data, $err_message = '', $connection = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ?: $database->getDefaultConnection();

        $count = $database->connection($connection)->table($table)->where($data)->count();

        $this->assertGreaterThan(0, $count, sprintf(
            "%s \nUnable to find row in database table [%s] that matched attributes [%s].", 
            $err_message, $table, json_encode($data)
        ));

        return $this;
    }


// ========================= SECTION 2: WRAPPERS =========================



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