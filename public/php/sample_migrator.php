<?php

    function load_samples_from_new_database(){


        $mysqli = new mysqli("localhost", "root", "", "zl4");

        if ($mysqli->connect_errno) {/* check connection */
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }

        $query = "SELECT * FROM dbs_samples, batches 
                    WHERE   batches.id=1 
                    AND     dbs_samples.migrated_to_old_schema='NO' 
                    AND     dbs_samples.batch_id = batches.id";

        
        $result = $mysqli->query($query)

        if ( !$result ){
            printf("SELECT query failed to get any data from new schema");
            exit();
        }


        while( $row = $result->fetch_assoc() ){ /* fetch associative array */

            printf ("%s (%s)\n", $row["Name"], $row["CountryCode"]);
        }

        /* free result set */
        $result->free();

        /* close connection */
        $mysqli->close();



##
######  Step 1 = save data about the infant's mother:
##
            $table->enum('migrated_to_old_schema', array('YES', 'NO'))->default('NO');
            $table->integer('lab_worksheet_number')->unsigned()->nullable()->default(NULL);


$seed = select 
$motherrec ="INSERT INTO mothers (
                facility,
                entry_point,
                feeding,
                antenalprophylaxis,
                deliveryprophylaxis,
                postnatalprophylaxis,
                status, <- unused in old code. It defaults to 0 and in fact 99.99% of values are 0. redundant field, since all mothers in this program are assumed HIV+.
                batchno,
                labtestedin, <-- defaults to 1: CPHL's EID lab
                fcode, <-- defaults to 0. all values are 0. usage is unclear.
                synched) <-- defaults to 0. all values are 0. usage is unclear.

$mother_id = DB::table('mothers')->insertGetId( array('email' => 'john@example.com', 'votes' => 0) );
$lab_number = current_sample.sample_id;


    $stmt = $mysqli->prepare("INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
    $stmt->bind_param('sssd', $code, $language, $official, $percent);


            $stmt = $mysqli->prepare("INSERT INTO mothers (
                                                    facility,
                                                    entry_point,
                                                    feeding,
                                                    antenalprophylaxis,
                                                    deliveryprophylaxis,
                                                    postnatalprophylaxis,
                                                    status,
                                                    batchno,
                                                    labtestedin,
                                                    fcode,
                                                    synched
                                            )
                                            VALUES ( ?,?,?,?,?,?,?,?,?,?,? )";

            $stmt->bind_param($facility_id, 
            entry_point, is_breast_feeding,
                );

    	                       id
                         batch_id
                     pos_in_batch
               sample_verified_by
                           nSpots
                  sample_rejected
              rejection_reason_id
               rejection_comments
                      infant_name
                    infant_exp_id
                    infant_gender
                       infant_age
                       infant_dob
         infant_is_breast_feeding
                infant_entryPoint
             infant_contact_phone
     mother_antenatal_prophylaxis
      mother_delivery_prophylaxis
     mother_postnatal_prophylaxis
               infant_prophylaxis
                   date_dbs_taken
                  date_dbs_tested
             date_results_entered
             date_results_printed
           migrated_to_old_schema
             lab_worksheet_number
                        test_type
                   test_labNumber
                      test_result
                              pcr
                      lab_comment
                               id
                              lab
                     batch_number
                  envelope_number
                      facility_id
                    facility_name
                facility_district
                     senders_name
                senders_telephone
                 senders_comments
           results_return_address
         results_transport_method
    date_dispatched_from_facility
                date_rcvd_by_cphl
               date_entered_in_DB
           date_PCR_testing_completed
      date_dispatched_to_facility
            date_rcvd_at_facility
    }
