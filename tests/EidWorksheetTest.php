<?php

use EID\Models\Worksheet;
use EID\Lib\WorksheetManager;

use Illuminate\Foundation\Testing\WithoutMiddleware;


define('ACCESSION_NUMBER', 1234567);
define('INCONCLUSIVE', 'NULL');


class EidWorksheetTest extends TestCase
{ 
    use HttpTrait;
    use WithoutMiddleware;
    use DbsTrait;

    public function test_generateTestID_ifNoTestsDoneYet_ReturnsSlash1()
    {
        $nTestsDone = 0;
        $test_id = $this->generateTestID( $nTestsDone );

        $this->assertTrue( $test_id === ACCESSION_NUMBER."/1" );
    }

    public function test_generateTestID_if1TestsDone_ReturnsSlash2()
    {
        $nTestsDone = 1;
        $test_id = $this->generateTestID( $nTestsDone );

        $this->assertTrue( $test_id === ACCESSION_NUMBER."/2[RPT]" );
    }

    public function test_generateTestID_if2TestsDone_ReturnsSlash3()
    {
        $nTestsDone = 2;
        $test_id = $this->generateTestID( $nTestsDone );

        $this->assertTrue( $test_id === ACCESSION_NUMBER."/3[RPT]" );
    }
    public function test_generateTestID_if3TestsDone_ReturnsSlash4()
    {
        $nTestsDone = 3;
        $test_id = $this->generateTestID( $nTestsDone );

        $this->assertTrue( $test_id === ACCESSION_NUMBER."/4[RPT]" );
    }

    public function test_ParseRow_GivenNegativeReturnsNegative(){

        list($wsm, $dc) = $this->getWorksheetManager();
        $negative_row = $dc->save_test_results(12345, NEGATIVE);
        $negative_result = $wsm->parseRow( $negative_row );

        $this->assertTrue( $negative_result === NEGATIVE );
    }

    public function test_ParseRow_GivenPositiveReturnsPositive()
    {
        list($wsm, $dc) = $this->getWorksheetManager();
        $positive_row = $dc->save_test_results(12345, POSITIVE);
        $positive_result = $wsm->parseRow( $positive_row );
        
        $this->assertTrue( $positive_result === POSITIVE );
    }


    public function test_ParseRow_GivenLowPositiveReturnsLowPositive(){

        list($wsm, $dc) = $this->getWorksheetManager();
        $low_positive_row = $dc->save_test_results(12345, LOW_POSITIVE);
        $low_positive_result = $wsm->parseRow( $low_positive_row );

        $this->assertTrue( $low_positive_result === LOW_POSITIVE );    
    }


    public function test_ParseRow_GivenFailOrInvalidReturnsFail(){

        list($wsm, $dc) = $this->getWorksheetManager();

        $fail_row = $dc->save_test_results(12345, FAIL);
        $fail_result = $wsm->parseRow( $fail_row );

        $invalid_row = $dc->save_test_results(12345, INVALID);
        $invalid_result = $wsm->parseRow( $invalid_row );

        $this->assertTrue( $fail_result === FAIL );
        $this->assertTrue( $invalid_result === FAIL );
    }


    public function test_countFailedTests_Given1_Returns1()
    {
        $nFailedTests = $this->countFailedTests( 1 );
        $this->assertTrue( $nFailedTests == 1 );
    }
    public function test_countFailedTests_Given2_Returns2()
    {
        $nFailedTests = $this->countFailedTests( 2 );
        $this->assertTrue( $nFailedTests == 2 );
    }
    public function test_countFailedTests_Given3_Returns3()
    {
        $nFailedTests = $this->countFailedTests( 3 );
        $this->assertTrue( $nFailedTests == 3 );
    }
    public function test_countFailedTests_Given4_Returns4()
    {
        $nFailedTests = $this->countFailedTests( 4 );
        $this->assertTrue( $nFailedTests == 4 );
    }
    public function test_countFailedTests_Given5_Returns5()
    {
        $nFailedTests = $this->countFailedTests( 5 );
        $this->assertTrue( $nFailedTests == 5 );
    }



    public function test_quote_GivenNull_ReturnsNullStr()
    {
        list($wsm, ) = $this->getWorksheetManager();
        $result = $wsm->quote(null);

        $this->assertTrue( $result === "NULL");
    }

    public function test_quote_GivenNullstr_ReturnsNullStr()
    {
        list($wsm, ) = $this->getWorksheetManager();
        $result = $wsm->quote("NULL");

        $this->assertTrue( $result === "NULL");
    }

    public function test_quote_GivenStr_ReturnsQuotedStr()
    {
        list($wsm, ) = $this->getWorksheetManager();
        $result = $wsm->quote("string");

        $this->assertTrue( $result === "'string'");
    }


    public function test_DefaultSCDReadiness(){

        $this->createSamplesForEidWorksheet();
        list($wsm, $dc) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, FAIL);

        $this->assertTrue($sample['ready_for_SCD_test'] == 'NO');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }


    public function test_DefaultSCDReadiness_WhenReadinessIsYes(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['ready_for_SCD_test' => 'YES']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, FAIL );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'YES');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }

    public function test_SCDReadiness_WhenTestAlreadyDone(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['ready_for_SCD_test' => 'TEST_ALREADY_DONE']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, FAIL );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'TEST_ALREADY_DONE');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }

    public function test_SCDReadiness_When1stValidResultIsNegative(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'YES']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, NEGATIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'YES');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }

    public function test_SCDReadiness_When1stValidResultIsNegativeButSCDTestNotRequested(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'NO']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, NEGATIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'NO');
        $this->assertTrue($sample['SCD_test_requested'] == 'NO');
    }


    public function test_SCDReadiness_When1stValidResultIsLowPositive(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'YES']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, LOW_POSITIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'NO');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }


    public function test_SCDReadiness_When1stValidResultIsLowPositiveAfter3rdRepeat(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'YES' ]);

        list($wsm, ) = $this->getWorksheetManager();
        $wsm->setNumOfWorksheets(DBS_SAMPLE_ID, 3);// Note: after 2nd repeat (hence $nWorksheets > 2)
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, POSITIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'YES');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }


    public function test_SCDReadiness_When1stValidResultIsPositive(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'YES']);

        list($wsm, ) = $this->getWorksheetManager();
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, POSITIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'NO');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }


    public function test_SCDReadiness_When1stValidResultIsPositiveAfter2ndRepeat(){

        $this->createSamplesForEidWorksheet();
        $this->modify_dbs_row(DBS_SAMPLE_ID, ['SCD_test_requested' => 'YES' ]);

        list($wsm, ) = $this->getWorksheetManager();
        $wsm->setNumOfWorksheets(DBS_SAMPLE_ID, 3);// Note: after 2nd repeat (hence $nWorksheets > 2)
        $sample = $wsm->get_SCD_testReadiness( DBS_SAMPLE_ID, POSITIVE );

        $this->assertTrue($sample['ready_for_SCD_test'] == 'YES');
        $this->assertTrue($sample['SCD_test_requested'] == 'YES');
    }


    public function test_interpreteResults_Scenario1Z_isNegative()
    {
        $test_result = $this->interpreteResults([NEGATIVE], '1Z');

        $this->assertTrue($test_result === NEGATIVE);
    }


    public function test_interpreteResults_Scenario2XP_isPositive()
    {
        $test_result = $this->interpreteResults([POSITIVE, POSITIVE], '2XP');

        $this->assertTrue($test_result === POSITIVE);
    }


    public function test_interpreteResults_ScenarioLowPositive2XP_isPositive()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, POSITIVE], '2XP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_Scenario3XFP_isPositive()
    {
        $test_result = $this->interpreteResults([FAIL, POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_ScenarioPositive3XFP_isPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Low Positive is first):
        $test_result = $this->interpreteResults([FAIL, POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_ScenarioLowPositive3XFP_isPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Low Positive is first):
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_ScenarioLowPositive3XFP_isInconclusiveIfPositiveComesB4LowPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Positive is first):
        $test_result = $this->interpreteResults([FAIL, POSITIVE, LOW_POSITIVE], '3XFP');

        $this->assertTrue($test_result === INCONCLUSIVE);
    }

    
    public function test_interpreteResults_Scenario4XF_isNegative()
    {
        $test_result = $this->interpreteResults([FAIL, NEGATIVE], '4XF');

        $this->assertTrue($test_result === NEGATIVE);
    }

    public function test_interpreteResults_Scenario4Z4Z_isInvalid()
    {
        $test_result = $this->interpreteResults([FAIL, FAIL, FAIL, FAIL, FAIL], '4Z4Z');

        $this->assertTrue($test_result === INVALID);
    }

    public function test_interpreteResults_Scenario5XQQ_isPositive()
    {
        $test_result = $this->interpreteResults([POSITIVE, LOW_POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_ScenarioLowPositive5XQQ_isPositive()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, LOW_POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_ScenarioLowPositive5XQQ_isPositive2()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_Scenario6XQN_isNegative()
    {
        $test_result = $this->interpreteResults([POSITIVE, NEGATIVE, NEGATIVE], '6XQN');

        $this->assertTrue($test_result === NEGATIVE);
    }    

    public function test_interpreteResults_ScenarioLowPositive6XQN_isNegative()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, NEGATIVE, NEGATIVE], '6XQN');

        $this->assertTrue($test_result === NEGATIVE);
    }

    public function test_interpreteResults_Scenario7XQQ_isInvalid()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, LOW_POSITIVE, NEGATIVE], '7XQQ');

        $this->assertTrue($test_result === INVALID);
    }    

    public function test_interpreteResults_ScenarioLowPositive8XQN_isInvalid()
    {
        $test_result = $this->interpreteResults([LOW_POSITIVE, NEGATIVE, LOW_POSITIVE], '8XQN');

        $this->assertTrue($test_result === INVALID);
    }    

    public function test_interpreteResults_Scenario8XQN_isInvalid()
    {
        $test_result = $this->interpreteResults([POSITIVE, NEGATIVE, POSITIVE], '8XQN');

        $this->assertTrue($test_result === INVALID);
    }    


    public function interpreteResults($results, $expected_scenario)
    {
        $this_result = array_pop( $results );
        $prev_results = $results;// Note: it's after array_pop()

        $wsm = $this->makeWorksheetManager( $prev_results );
        $EID = $wsm->interpreteResults(DBS_SAMPLE_ID, $this_result, $expected_scenario);
        
        $accepted_result = $EID['accepted_result'];

        return $wsm->unQuote( $accepted_result );
    }

    public function makeWorksheetManager( $prev_results_data )
    {
        $this->createSamplesForEidWorksheet();
        $wsm = new WorksheetManager( new Worksheet );

        $new_data = $this->prepare_eid_data( $prev_results_data );
        $sample = $wsm->updateSample( DBS_SAMPLE_ID, $new_data );
        
        return $wsm;
    }

    public function prepare_eid_data($prev_results_data)
    {
        $prev_eid_results = $this->fmtPreviousResults( $prev_results_data );

        return $prev_eid_results + ['nTestsDone' => count($prev_eid_results)];
    }

    public function fmtPreviousResults( $results )
    {
        $prev_results = [];
        $nPrev_results = count($results);

        for($i=0; $i < $nPrev_results; $i++)
            $prev_results["test_".($i+1)."_result"] = $results[ $i ];

        return $prev_results;
    }





    public function generateTestID( $n )
    {
        list($wsm, ) = $this->getWorksheetManager();
        $sample = $this->get_sample( ['accession_number' => ACCESSION_NUMBER, 'nTestsDone' => $n] );
        $test_id = $wsm->generateTestID( $sample );

        return $test_id;
    }


    public function get_sample( $overrides = [] )
    {
        if(!is_array( $overrides ))
            throw new Exception("get_sample() expected an array as input", 1);
            
        $defaults = [
            'testing_completed' => 'NO',
            'nTestsDone' => 0,
            'accession_number' => ACCESSION_NUMBER
        ];

        $seed_data = array_merge( $defaults, $overrides);
        $sample = factory('EID\Models\Sample')->make( $seed_data );

        return $sample;
    }


    public function countFailedTests( $n )
    {
        list($wsm, ) = $this->getWorksheetManager();
        $this_sample = $this->get_sample_with_failed_tests( $n );
        
        return $wsm->countFailedTests( $this_sample );
    }





// ============================================================================================================
//      The section below has all the above tests repeated with a FAIL as the first result
//      This is to demonstrate that failing tests do not spoil or change the final outcome
// ============================================================================================================



    public function test_interpreteResults_FailScenario1Z_isNegative()
    {
        //  Do nothing. 
        //  This case is tested by Scenario4XF and FailScenario4XF
    }


    public function test_interpreteResults_FAILScenario2XP_isPositive()
    {
        //  Do nothing. 
        //  This case is tested by Scenario3XFP and FailScenario3XFP
    }


    public function test_interpreteResults_FAILScenarioLowPositive2XP_isPositive()
    {
        //  Do nothing. 
        //  This case is tested by Scenario3XFP and FailScenario3XFP
    }

    public function test_interpreteResults_FAILScenario3XFP_isPositive()
    {
        $test_result = $this->interpreteResults([FAIL, FAIL, POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenarioPositive3XFP_isPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Low Positive is first):
        $test_result = $this->interpreteResults([FAIL, POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenarioLowPositive3XFP_isPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Low Positive is first):
        $test_result = $this->interpreteResults([FAIL, FAIL, LOW_POSITIVE, POSITIVE], '3XFP');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenarioLowPositive3XFP_isInconclusiveIfPositiveComesB4LowPositive()
    {
        // Note order of results as compared to the other '3XFP' test (Positive is first):
        $test_result = $this->interpreteResults([FAIL, FAIL, POSITIVE, LOW_POSITIVE], '3XFP');

        $this->assertTrue($test_result === INCONCLUSIVE);
    }

    
    public function test_interpreteResults_FAILScenario4XF_isNegative()
    {
        $test_result = $this->interpreteResults([FAIL, FAIL, NEGATIVE], '4XF');

        $this->assertTrue($test_result === NEGATIVE);
    }


    public function test_interpreteResults_FAILScenario5XQQ_isPositive()
    {
        $test_result = $this->interpreteResults([FAIL, POSITIVE, LOW_POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenarioLowPositive5XQQ_isPositive()
    {
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, LOW_POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenarioLowPositive5XQQ_isPositive2()
    {
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, POSITIVE, LOW_POSITIVE], '5XQQ');

        $this->assertTrue($test_result === POSITIVE);
    }

    public function test_interpreteResults_FAILScenario6XQN_isNegative()
    {
        $test_result = $this->interpreteResults([FAIL, POSITIVE, NEGATIVE, NEGATIVE], '6XQN');

        $this->assertTrue($test_result === NEGATIVE);
    }    

    public function test_interpreteResults_FAILScenarioLowPositive6XQN_isNegative()
    {
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, NEGATIVE, NEGATIVE], '6XQN');

        $this->assertTrue($test_result === NEGATIVE);
    }

    public function test_interpreteResults_FAILScenario7XQQ_isInvalid()
    {
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, LOW_POSITIVE, NEGATIVE], '7XQQ');

        $this->assertTrue($test_result === INVALID);
    }    

    public function test_interpreteResults_FAILScenarioLowPositive8XQN_isInvalid()
    {
        $test_result = $this->interpreteResults([FAIL, LOW_POSITIVE, NEGATIVE, LOW_POSITIVE], '8XQN');

        $this->assertTrue($test_result === INVALID);
    }    

    public function test_interpreteResults_FAILScenario8XQN_isInvalid()
    {
        $test_result = $this->interpreteResults([FAIL, POSITIVE, NEGATIVE, POSITIVE], '8XQN');

        $this->assertTrue($test_result === INVALID);
    }    


// =============================================================================================================







    public function get_sample_with_failed_tests( $nFailedtests )
    {
        if($nFailedtests > 5) 
            $nFailedtests = 5;

        $this_sample = [];
        $this_sample["test_1_result"] = NEGATIVE;
        $this_sample["test_2_result"] = NEGATIVE;
        $this_sample["test_3_result"] = NEGATIVE;
        $this_sample["test_4_result"] = NEGATIVE;
        $this_sample["test_5_result"] = NEGATIVE;        

        for($i = 1; $i <= $nFailedtests; $i++ ){
            $this_sample["test_". $i ."_result"] = FAIL;
        }

        return $this_sample;
    }


    public function getWorksheetManager()
    {
        return [
            new WorksheetManager( new Worksheet ),
            new EID\Http\Controllers\DummyDataController
        ];
    }

}