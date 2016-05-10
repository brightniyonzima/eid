<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(EID\commodity_categories::class, function (Faker\Generator $faker) {
    
    return [
        'id' => $faker->numberBetween(5, 95),
        'category_name' => $faker->text(20)
    ];
});

$factory->define(EID\Models\User::class, function (Faker\Generator $faker) {

    $area_codes = ['70', '71', '75', '77', '78', '79'];
    $phone_number = "+256" . $faker->randomElement($area_codes) . $faker->randomNumber(7);
        
    return [

        'id'        => $faker->numberBetween(5, 10),
        'username'  => 'tester_' . $faker->userName,
        'password'  => bcrypt(str_random(15)),
        'type'      => 1,
        'is_admin'  => 1,        
        'email'     => $faker->email,
        'remember_token' => str_random(60),        
        'other_name'=> $faker->firstName,
        'family_name'   =>  $faker->lastName,
        'telephone' => $phone_number

    ];
});



$factory->define(EID\commodity_categories::class, function (Faker\Generator $faker) {
	
    return [
        'id' => $faker->numberBetween(5, 95),
        'category_name' => $faker->text(20)
    ];
});



$factory->define(EID\commodities::class, function (Faker\Generator $faker) {
	
    return [
    	'id' => $faker->numberBetween(5, 95),
        'commodity_name' => $faker->text(20),
        'category_id' => $faker->numberBetween(5, 95),
        'tests_per_unit' => $faker->numberBetween(1, 50)
    ];
});



$factory->define(EID\Models\Batch::class, function (Faker\Generator $faker) {

    $area_codes = ['70', '71', '75', '77', '78', '79'];
    $phone_number = "+256" . $faker->randomElement($area_codes) . $faker->randomNumber(7);
    
    $envelope_number = $faker->date('Ymd') . '_' . $faker->numberBetween(1, 25);

    return [
        'envelope_number' => $envelope_number,
        'batch_number' => $faker->randomNumber(7),
        'facility_id' => $faker->numberBetween(50, 1250),
        'senders_comments' => $faker->sentence(),
        'results_return_address' => $faker->address,
        'results_transport_method' => 'POSTA_UGANDA',


    // Note: date_dispatched_from_facility < date_rcvd_by_cphl < date_entered_in_DB
        'date_dispatched_from_facility' =>  $faker->dateTimeBetween('-30 days', '-20 days')->format('Y-m-d'),
        'date_rcvd_by_cphl'             =>  $faker->dateTimeBetween('-20 days', '-10 days')->format('Y-m-d'),
        'date_entered_in_DB'            =>  $faker->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),


        'facility_name' => $faker->name . " Hospital",
        'facility_district' => $faker->word,
        'senders_name' => $faker->name,
        'senders_telephone' => $phone_number,
        'all_samples_rejected' => 'YES',
        'PCR_results_released' => 'NO',
        'SCD_results_released' => 'NO',
        'tests_requested' => 'BOTH_PCR_AND_SCD',
        'f_paediatricART_available' => 'LEFT_BLANK',

    ];
});


$factory->define(EID\Models\Sample::class, function (Faker\Generator $faker) {

    // Note:
    // ALWAYS call factory->make() or factory->create() with 'batch_id'   
    // Also provide pos_in_batch and pos_in_workSheet when generating more than 1 sample
    

    $envelope_number = $faker->date('Ymd') . '_' . $faker->numberBetween(1, 25);
    $accession_number = $faker->randomNumber(7);

    return [
        'infant_name'   => $faker->name,
        'infant_exp_id' => $faker->randomNumber(4),
        'infant_dob' => '2015-10-11',
        'infant_age' => '1 month',

        'pos_in_batch'  => 1,
        'date_dbs_taken'=> $faker->dateTimeThisMonth()->format('Y-m-d'),
        'PCR_test_requested' => 'YES',
        'SCD_test_requested' => 'YES',

        'nSpots' => 5,
        'sample_verified_on' => date('Y-m-d'),
        'sample_verified_by' => $faker->numberBetween(5, 10),
        'sample_rejected' => 'NO',
        'rejection_reason_id' => '',
        'rejection_comments' => null,
        'ready_for_SCD_test' => 'NO',
        'sickle_cell_release_code' => null,

        'pcr' => 'NON_ROUTINE',

        // 'accession_number' => $accession_number,

        'worksheet_1' => null,
        'worksheet_2' => null,
        'worksheet_3' => null,
        'worksheet_4' => null,
        'worksheet_5' => null,

        'test_1_result' => null,
        'test_2_result' => null,
        'test_3_result' => null,
        'test_4_result' => null,
        'test_5_result' => null,

        'in_workSheet' => 'NO',
        'pos_in_workSheet' => 1,
        // 'nWorksheets' => 0,
        'testing_completed' => 'NO',
        'accepted_result' => null,
        // 'current_test_id' => $accession_number . "/1",
        'physical_location' => '',
        // 'nTestsDone' => 0

    ];
});