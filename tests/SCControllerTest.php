<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

define('FIRST_SAMPLE_ID', 100);

class SCControllerTest extends TestCase
{

    use HttpTrait;
    use WithoutMiddleware;
    use DbsTrait;

    public function test_SCController_ifNewWorksheetCreated_SeeItInDB()
    {
        $this->auto_login();

        $scc = new EID\Http\Controllers\SCController;
        $scws_id = $scc->make_new_worksheet();

        $this->seeInDatabase('sc_worksheets', ['id' => $scws_id] );
    }

    public function test_SCManager_returnsCorrectNumberOfSamples()
    {
        $nSamples_in_sickle_cell_worksheet = \SCManager::getNumSamplesPerWorksheet();
        $this->assertEquals( $nSamples_in_sickle_cell_worksheet, SC_SAMPLES_PER_WORKSHEET );
    }

    public function test_SCController_getSamples_CorrectlyParsesItsInput()
    {
        $input_data = ['q' => "dbs_744841, dbs_744842, dbs_744843, "];
        $expected_sampleIDs =  "744841, 744842, 744843" ;

        $scc = new EID\Http\Controllers\SCController;
        $actual_sampleIDs = $scc->get_samples( $input_data );

        $this->assertEquals( $expected_sampleIDs, $actual_sampleIDs );
    }

    public function test_SCController_ifTooFewSamples_ReturnNegativeNumber()
    {
        $not_enough_samples = ['q' => "dbs_744841, dbs_744842,"];
        $http_reply = $this->http_get( "/scws_store/", $not_enough_samples);
        $return_value_is_negative = ((int) $http_reply->response->content()) < 0 ? true : false ;

        $this->assertTrue( $return_value_is_negative );
    }

    public function test_SCController_ifEnoughSamples_ReturnPositiveNumber()
    {
        $enough_samples = $this->getEnoughSamplesForSCworksheet();
        $http_reply = $this->http_get( "/scws_store/", $enough_samples);
        $worksheet_number = ((int) $http_reply->response->content());
        $return_value_is_positive =  $worksheet_number > 0 ? true : false ;
        $last_sample_id = FIRST_SAMPLE_ID + SC_SAMPLES_PER_WORKSHEET;
        $randomly_chosen_sample_id = mt_rand(FIRST_SAMPLE_ID, $last_sample_id);

        $this->assertTrue( $return_value_is_positive );
        $this->seeInDatabase('sc_worksheets', ['id' => $worksheet_number]);
        
        $this->seeInDatabase('sc_worksheet_index', [ 'sample_id' => $randomly_chosen_sample_id ]);
        $this->seeInDatabase('sc_worksheet_index', [ 'sample_id' => $randomly_chosen_sample_id,
                                                     'worksheet_number' => $worksheet_number]);
        
        $this->seeInDatabase('dbs_samples', ['id' => $randomly_chosen_sample_id ]);
        $this->seeInDatabase('dbs_samples', ['id' => $randomly_chosen_sample_id, 
                                             'ready_for_SCD_test' => 'TEST_ALREADY_DONE']);
    }


    public function getEnoughSamplesForSCworksheet()
    {
        $this->get_existing_dbs_data( FIRST_SAMPLE_ID, BATCH_ID, (SC_SAMPLES_PER_WORKSHEET*-1));
        $this->accept_all_for_SCD();
        $str = $this->format_SCDSampleIDs_AsString( FIRST_SAMPLE_ID, SC_SAMPLES_PER_WORKSHEET);

        return ['q' => $str];
    }


    public function format_SCDSampleIDs_AsString($first_sample_id, $nSamples)
    {
        $str = "";

        for ($i=0; $i < $nSamples; $i++) { 
            $new_sample_id = $first_sample_id + $i;
            $str .= "dbs_" . $new_sample_id . ",";
        }
        
        return $str;
    }


}
/*
    //     "dbs_744866,dbs_744960,dbs_744942,dbs_744828,dbs_744923,
    //     dbs_744798,dbs_744910,dbs_744665,dbs_744884,dbs_744974,dbs_744955,dbs_744844,dbs_744928,dbs_744919,dbs_744705,dbs_745006,dbs_744899,dbs_744871,dbs_744962,dbs_744856,dbs_744947,dbs_744836,dbs_744924,dbs_744800,dbs_744913,dbs_744671,dbs_744984,dbs_744892,dbs_744864,dbs_744956,dbs_744849,dbs_744935,dbs_744822,dbs_744920,dbs_744718,dbs_744755,dbs_744901,dbs_744875,dbs_744966,dbs_744950,dbs_744840,dbs_744926,dbs_744802,dbs_744917,dbs_744676,dbs_744895,dbs_744936,dbs_744827,dbs_744922,dbs_744722,dbs_744904,dbs_744877,dbs_744972,dbs_744952,dbs_744841,dbs_744927,dbs_744814,dbs_745005,dbs_744898,dbs_732862,dbs_744540,dbs_744499,dbs_744536,dbs_744493,dbs_744542,dbs_744537,dbs_744495,dbs_744546,dbs_744539,dbs_744497,dbs_744550,dbs_743618,dbs_743634,dbs_743531,dbs_743626,dbs_743572,dbs_743639,dbs_743613,dbs_743579,dbs_743554,"
*/