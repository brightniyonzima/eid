<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{ font-family:times, sans-serif;
        font-size:10px;
        padding:3px 10px;
        border-style:solid;
        border-width:1px;
        overflow:hidden;
        word-break:normal; 
        border:none
      }

.tdr{ text-align: right;}
</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the current printer page size */
        /*margin: 0mm;   this affects the margin in the printer settings */
        margin: 1em;  /* the margin on the content before printing */

    }
    @media print {
      footer {page-break-after: always;}
    }

    body 
    {
        background-color:#FFFFFF; 
        /* border: solid 1px black ; */
        margin: 1em;  /* the margin on the content before printing */

   }
</style>


<?php /*  This code is copied from eron.blade.php and should be identical to it so
          that it prints exactly the envelopes expected by eron.blade.php in exactly the order expected

          Of course this code should be refactored into a class which they both call...
          (Update: but i expect it to be run only once, so i have not done so)
      */



  $filter = " dbs_samples.date_results_entered = '1905-11-26'"; /* added for this special case */


  $REJECTION_REASONS = "6";

  $sql =   "SELECT  DISTINCT
  
                  dbs_samples.id as axnNo, 
  
                    infant_name, infant_gender,
                    infant_exp_id as expID,
                    date_dbs_taken as collected,
                    infant_age as age,
                    facility_id as HC,
                    batch_number,
                    batch_id,
                    users.id as user_id,
                    if(users.id = PCR_results_ReleasedBy, 'PCR', 'SCD') as test,
  
                    date_rcvd_by_cphl,
                    date_results_entered,
                    date_dbs_tested as assay_date,
                    accepted_result as PCR_result, 
                    SCD_test_result as SCD_result, 
                    facilities.facility as facility_name, 
                    districts.name as district_name ,
                    PCR_results_ReleasedBy,
                    SCD_results_ReleasedBy,
                    family_name,
                    other_name,
                    signature,
  
  
                    sample_rejected,
                    rejection_reason_id, 
                    appendix as rejection_reason_str
  
  
            FROM 

                  dbs_samples

                  LEFT JOIN  batches     ON  dbs_samples.batch_id = batches.id
                  LEFT JOIN  facilities  ON  batches.facility_id = facilities.id
                  LEFT JOIN  districts   ON  districts.id = facilities.districtID 
                  LEFT JOIN  users ON (users.id = PCR_results_ReleasedBy OR users.id = SCD_results_ReleasedBy) 
                  LEFT JOIN   appendices ON   (categoryID = 6 AND (rejection_reason_id = appendices.id))
              
  
            WHERE $filter  /* dbs_samples.batch_id = '200205' */
    
            ORDER BY dbs_samples.id ASC";
?>


<?php

    function getRejectedBatches($sql) 
    {

      $arr = [];
      $db_rows = \DB::select($sql);

      foreach ($db_rows as $this_row) {
        $facility_id = $this_row->HC;
        $batch_id = $this_row->batch_id;

        $arr[ $facility_id ] = $batch_id; // This prevents printing multiple envelopes for the same facility.
                                          // Batches are needed by envelope printer, so 1 batch per facility is enough.
      }

      $quoted_batchIDs = "'" . implode("', '", $arr) . "'";

      return $quoted_batchIDs;
    }


    $batch_id = getRejectedBatches($sql);

    $sql = "SELECT  senders_name , senders_telephone , facilities.facility as facility_name, 
                    facility_district, phone, contactPerson, hub 
                FROM    batches, facilities, hubs 
                WHERE   batches.facility_id = facilities.id and batches.id IN ($batch_id)
                AND     facilities.hubID = hubs.id

                ORDER BY facility_name, batches.id";


    $result = DB::select($sql);

    $nResults = count($result);
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

@foreach($result as $envelope)

    <div style="margin-left: 0em;"> 

        <h1 style="text-align:right"> {{ $envelope->hub}}&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <h2>{{ $envelope->facility_name }}</h2>
        <h2>District: {{ $envelope->facility_district }}</h2>
        <h2>{{ $envelope->senders_name }}</h2>
        <h2>{{ $envelope->senders_telephone }}</h2>
      <br>
        <h4>C/O :</h4>
        <h4>Sickle Cell Results</h4>

    </div>
    <footer></footer>
@endforeach
</body>
</html>

<?php if(Request::has('pp')) 
        $print_me = "window.onload = function() { window.print() }";
      else
        $print_me = "";
?>  

<script type="text/javascript">
      {{ $print_me }}

</script>