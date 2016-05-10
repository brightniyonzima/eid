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


<?php /*  This code is copied from rejected_results.blade.php and should be identical to it so
          that it prints exactly the envelopes expected by results.blade.php in exactly the order expected

          Of course this code should be refactored into a class which they both call...
      */


  function getDateRange()
  {


    $start_date = Request::get('from','');
    $end_date = Request::get('to', '');
    $and = "";

    if(empty($start_date) && empty($end_date))// both empty
        return null;

    if(!empty($start_date) && !empty($end_date))// both have values
        $and = " AND ";

    if(!empty($start_date))
        $start_date = " sample_verified_on >= '$start_date' ";

    if(!empty($end_date))
        $end_date = " sample_verified_on <= '$end_date' ";

    return $start_date . $and . $end_date;
  }



  $date_verified = getDateRange();
  
  $REJECTION_REASONS = "6";

  $sql = "SELECT  DISTINCT

                dbs_samples.id as axnNo, 

                  sample_verified_on,
                  sample_rejected,
                  infant_name, infant_gender,
                  infant_exp_id as expID,
                  date_dbs_taken as collected,
                  infant_age as age,
                  facility_id as HC,
                  batch_number,
                  batch_id,

                  date_rcvd_by_cphl,
                  date_results_entered,

                  users.id as user_id,
                  if(users.id = PCR_results_ReleasedBy, 'PCR', 'SCD') as test,

                  batches.date_dispatched_to_facility as assay_date,
                  accepted_result as PCR_result, 
                  SCD_test_result as SCD_result, 
                  facilities.facility as facility_name, 
                  districts.name as district_name ,
                  PCR_results_ReleasedBy,
                  SCD_results_ReleasedBy,
                  family_name,
                  other_name,
                  signature,
                  rejection_reason_id, 
                  appendix as rejection_reason_str

          FROM batches, dbs_samples, districts, facilities , users, appendices

          WHERE   sample_rejected = 'YES'
            AND   batches.id = dbs_samples.batch_id 
            AND   batches.facility_id = facilities.id 
            AND   facilities.districtID = districts.id 
            AND   users.id = dbs_samples.sample_verified_by 
            AND   categoryID = $REJECTION_REASONS 
            AND   rejection_reason_id = appendices.id
            AND   $date_verified

            ORDER BY sample_verified_on DESC, facility_name, batch_id";

?>


<?php



  function show_rejects($batch_id_from_controller)
  {

    if($batch_id_from_controller == 0 && (Request::has('from') || Request::has('to')) ){
      return true;
    }
    else
      return false;
  }

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


  // dd($b);
  // dd('Envelopes!');


  $batch_id = Request::get('b') ?: 0;
  $batch_id = "'" . $batch_id . "'";

  $batch_id = empty($b) ? $batch_id: $b;// $b comes from the controller

  if( show_rejects($b) ){

    if($date_verified == null)
      dd('Please choose a start date or an end date or both');

    $batch_id = getRejectedBatches($sql);
  }


  if($batch_id)
      $filter = "dbs_samples.batch_id = '$batch_id'";
  else
      dd('No batch ID found');

  $sql = "SELECT  senders_name , senders_telephone , facilities.facility as facility_name, 
                  facility_district, phone, contactPerson, hub 
              FROM    batches, facilities, hubs 
              WHERE   batches.facility_id = facilities.id and batches.id IN ($batch_id)
              AND     facilities.hubID = hubs.id

              ORDER BY facility_name, batches.id";

    // dd($sql);

    $result = DB::select($sql);


    $nResults = count($result);

    $ENVELOPE = $nResults > 0 ? "ENVELOPES" : "ENVELOPE";
    $show_print_links = \Request::has('pp') ? false : true;
    $current_URL = \Request::fullUrl();
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

  @if($show_print_links)
    <center >
      <a href="{{ $current_URL }}?&pp=1" target="_blank" style="font-size: xx-large">PRINT {{ $ENVELOPE }} </a>
    </center>
  @endif

@foreach($result as $envelope)

    <div style="margin-left: 0em;"> 

        <h1 style="text-align:right"> {{ $envelope->hub}}&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <h2>{{ $envelope->facility_name }}</h2>
        <h2>District: {{ $envelope->facility_district }}</h2>
        <h2>{{ $envelope->senders_name }}</h2>
        <h2>{{ $envelope->senders_telephone }}</h2>
      <br>
        <h4>C/O :</h4>
        <h4>EID Results</h4>

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