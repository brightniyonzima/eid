<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;



class ApprovalTest extends TestCase
{
/*
 * NOTE:
 *  functions ending in '_dbs_data' or '_batch_data' expect or returns an array
 *  functions ending in '_dbs' or '_batch' expect or return a model
**/
 
    use HttpTrait;
    use WithoutMiddleware;
    use DbsTrait;

// ============================== SECTION 1: TESTS ===================================


    public function test_SampleApproval_ifSampleDoesNotExist_Return404()
    {
        $err_message = 'Trying to fetch non-existent sample should fail with http status code 404';
        $this->http_get('/dbsVerify/'.NON_EXISTENT_SAMPLE, [], 404, $err_message );
    }


    public function test_SampleApproval_ifReadyforSCDTest_ReturnJSON()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $scd_readiness_data = $this->ready_for_SCD_test( YES );

        $approval_data = array_merge($sample_data, $scd_readiness_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        $reply->seeJson(['sample_id' => DBS_SAMPLE_ID]);
    }


    public function test_SampleApproval_ifSampleRejectedForPCR_ReturnJSON()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_rejection_data = $this->reject_for_PCR( );

        $approval_data = array_merge($sample_data, $PCR_rejection_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        $reply->seeJson(['sample_id' => DBS_SAMPLE_ID]);
    }




    public function test_SampleApproval_ifSampleRejectedForSCD_ReturnJSON()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_rejection_data = $this->reject_for_SCD( );

        $approval_data = array_merge($sample_data, $PCR_rejection_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        $reply->seeJson(['sample_id' => DBS_SAMPLE_ID]);
    }

    public function test_SampleApproval_ifSampleRejectedForSCD_SeeItInDB()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_rejection_data = $this->reject_for_SCD( );

        $approval_data = array_merge($sample_data, $PCR_rejection_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $expected_data = ['SCD_test_requested'  => YES,
                             'sample_rejected'  => YES,
                          'ready_for_SCD_test'  => NO,
                                          'id'  => DBS_SAMPLE_ID];

        $this->seeInDatabase('dbs_samples', $expected_data);
    }


    public function test_SampleApproval_ifSampleAcceptedForSCD_SeeItInDB()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_SCD();

        $approval_data = array_merge($sample_data, $PCR_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $expected_data = ['SCD_test_requested'  => YES,
                             'sample_rejected'  => NO,
                                          'id'  => DBS_SAMPLE_ID];

        $this->seeInDatabase('dbs_samples', $expected_data);
    }

    public function test_SampleApproval_ifSampleAcceptedForSCD_ReturnJSON()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_SCD();

        $approval_data = array_merge($sample_data, $PCR_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $reply->seeJson(['sample_id' => DBS_SAMPLE_ID]);        
    }


    public function test_SampleApproval_ifSampleRejectedForPCR_SeeItInDB()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_rejection_data = $this->reject_for_PCR( );

        $approval_data = array_merge($sample_data, $PCR_rejection_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $expected_data = ['PCR_test_requested'  => YES,
                             'sample_rejected'  => YES,
                                          'id'  => DBS_SAMPLE_ID];

        $this->seeInDatabase('dbs_samples', $expected_data);
    }


    public function test_SampleApproval_ifSampleAcceptedForPCR_SeeItInDB()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_PCR();

        $approval_data = array_merge($sample_data, $PCR_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $expected_data = ['PCR_test_requested'  => YES,
                             'sample_rejected'  => NO,
                                          'id'  => DBS_SAMPLE_ID];

        $this->seeInDatabase('dbs_samples', $expected_data);
    }


    public function test_SampleApproval_ifSampleAcceptedForPCR_ReturnJSON()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_PCR();

        $approval_data = array_merge($sample_data, $PCR_data);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $reply->seeJson(['sample_id' => DBS_SAMPLE_ID]);
    }


    public function test_SampleApproval_ifBatchNumberChanges_ReturnJSON()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );

        $PCR_data = $this->accept_for_PCR();

        $new_batch_number = ['batch_number' => 'NEW-BATCH-NUMBER'];// not numeric, so it's a change

        $approval_data = array_merge($sample_data, $PCR_data, $new_batch_number/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $reply->seeJson( $new_batch_number );
    }

    public function test_SampleApproval_ifBatchNumberChanges_SeeItInDB()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );

        $PCR_data = $this->accept_for_PCR();

        $new_batch_number = ['batch_number' => 'NEW-BATCH-NUMBER'];// not numeric, so it's a change

        $approval_data = array_merge($sample_data, $PCR_data, $new_batch_number/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $this->seeInDatabase('batches', $new_batch_number);
    }



    public function test_SampleApproval_ifNumberSpotsIsUnknown_RejectSample()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_PCR();
        $zero_db_spots = ['nSpots' => 'Unknown', 'rejection_reason_id' => DBS_SAMPLE_NOT_RECEIVED];

        $approval_data = array_merge($sample_data, $PCR_data, $zero_db_spots/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);
        
        $this->findInDatabase('dbs_samples', $zero_db_spots, '[Error: Unexpected rejection_reason_id]');
    }
    

    public function test_SampleApproval_ifNumberSpotsIsUnknown_CheckRejectionReason()
    {
        $correct_reason = DBS_SAMPLE_NOT_RECEIVED;
        $wrong_reason = DBS_SAMPLE_NOT_RECEIVED + 1;// any number except DBS_SAMPLE_NOT_RECEIVED;

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID );
        $PCR_data = $this->accept_for_PCR();
        $zero_db_spots = [ 'nSpots' => 'Unknown', 'rejection_reason_id' => $wrong_reason ];

        $approval_data = array_merge($sample_data, $PCR_data, $zero_db_spots/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $reply->see('error')->seeJson(['expected' => $correct_reason]);
    }
    

    public function test_SampleApproval_ifAllSamplesRejectedForPCR_ReleaseResultsImmediately()
    {

        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID, BATCH_ID );
        $PCR_data = $this->reject_all_for_PCR();

        $approval_data = array_merge($sample_data, $PCR_data/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $err_message = '\nIf all samples are rejected, PCR_results_released should be ' . YES;

        $this->findInDatabase('batches', ['id' => BATCH_ID, 'PCR_results_released' => YES], $err_message);
        
    }

    public function test_SampleApproval_ifAllSamplesRejectedForSCD_ReleaseResultsImmediately()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID, BATCH_ID );
        $SCD_data = $this->reject_all_for_SCD();

        $approval_data = array_merge($sample_data, $SCD_data/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $err_message = '\nIf all sickle-cell samples are rejected, SCD_results_released should be ' . YES;

        $this->findInDatabase('batches', ['id' => BATCH_ID, 'SCD_results_released' => YES], $err_message);
    }


    public function test_SampleApproval_ifNotAllSamplesRejectedForSCD_DoNotReleaseResults()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID, BATCH_ID );
        $SCD_data = $this->reject_for_SCD();

        $approval_data = array_merge($sample_data, $SCD_data/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $err_message = "\nNot all samples are rejected, so SCD_results_released should be " . NO;

        $this->findInDatabase('batches', ['id' => BATCH_ID, 'SCD_results_released' => NO], $err_message);
    }

    public function test_SampleApproval_ifNotAllSamplesRejectedForPCR_DoNotReleaseResults()
    {
        $sample_data = $this->get_existing_dbs_data( DBS_SAMPLE_ID, BATCH_ID );
        $PCR_data = $this->reject_for_PCR();

        $approval_data = array_merge($sample_data, $PCR_data/* Note: it's last */);

        $reply = $this->http_get('/dbsVerify/'.DBS_SAMPLE_ID, $approval_data);

        $err_message = "\nNot all samples are rejected, so PCR_results_released should be " . NO;

        $this->findInDatabase('batches', ['id' => BATCH_ID, 'PCR_results_released' => NO], $err_message);
    }
}