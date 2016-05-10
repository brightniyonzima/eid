<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}

.tg td{ font-family:times, sans-serif;
        font-size:12px;
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

<?php


    function display_comment($data)
    {
        $comment = "";

        if($data->sample_rejected == "YES"){
          $comment = $data->rejection_reason_str;
        }

        return $comment;
    }

    function goToNextRow($this_row)
    {

      if(empty($this_row->PCR_results_ReleasedBy) || empty($this_row->SCD_results_ReleasedBy)){
        // one (or both) of the signatories are missing. no need to fetch extra row
        
        return false;
      }

      if($this_row->PCR_results_ReleasedBy == $this_row->SCD_results_ReleasedBy){
      // same signatory for both results i.e no need to fetch extra row (bcoz its the same as this)

        return false;
      }

      return true;// fetch next row in all other cases
    }




  $dbs = Request::get('dbs') ?: 0;
  $batch_id = Request::get('b') ?: 0;

  if($batch_id)
      $filter = "dbs_samples.batch_id = '$batch_id'";
  else
      $filter = "dbs_samples.id = $dbs";

  $ADMIN = "53";
  $REJECTION_REASONS = "6";

  $sql =   "SELECT  
  
                  dbs_samples.id as axnNo, 
  
                    infant_name, infant_gender,
                    infant_exp_id as expID,
                    date_dbs_taken as collected,
                    infant_age as age,
                    facility_id as HC,
                    batch_number,

                     users.id as user_id,
                     if(users.id = PCR_results_ReleasedBy, 'PCR', 'SCD') as test, 
                      family_name,
                      other_name,
                      signature,

                     
                      /*
                    $ADMIN as user_id,
                    'SYSTEM' as family_name,
                    'Admin' as other_name,
                    '' as signature,
                    'SCD' as test,
                    */
  
                    date_rcvd_by_cphl,
                    date_results_entered,
                    date_dbs_tested as assay_date,
                    accepted_result as PCR_result, 
                    SCD_test_result as SCD_result, 
                    facilities.facility as facility_name, 
                    districts.name as district_name ,
                    PCR_results_ReleasedBy,
                    SCD_results_ReleasedBy,
  
  
                    sample_rejected,
                    rejection_reason_id, 
                    appendix as rejection_reason_str
  
  
            FROM 

                  dbs_samples

                  LEFT JOIN  batches     ON  dbs_samples.batch_id = batches.id
                  LEFT JOIN  facilities  ON  batches.facility_id = facilities.id
                  LEFT JOIN  districts   ON  districts.id = facilities.districtID 
                  LEFT JOIN  users       ON  (users.id = if(SCD_results_ReleasedBy is null, $ADMIN, SCD_results_ReleasedBy) ) 
                  LEFT JOIN   appendices  ON  (categoryID = $REJECTION_REASONS AND (rejection_reason_id = appendices.id))
              
  
            WHERE $filter  /* dbs_samples.batch_id = '200205' */
    
            ORDER BY dbs_samples.id ASC";

            //
            //
            // add a where (via UI) to filter by districts, facilities, batches or individuals
            // UI could be a drop-down + textbox. 
            // drop-down has these options: districts, facilities, batches and individuals
            // If textbox is filled, use that as an ID of whatever dropbox has. 
            // If textbox's not filled, filter by the category
            //
            //
            // dd($sql);

    $result = DB::select($sql);

// dd($result);

    $nResults = count($result);

// dd($nResults);


?>

<!DOCTYPE html>
<html>
<head>
  <title>EID Results</title>
</head>
<body>


@for($i=0; $i < $nResults; $i++)
<?php $r = $result[$i];?>
<div style="height: 7.75em; border: 1px solid white">&nbsp;</div>
<table class="tg" align="center" border="0">
  
  <tr>
    <td class="tg-031e" >Infant Name: </td>
    <td class="tg-031e" ><b>{{ ucwords(strtolower($r->infant_name)) }}</b></td>
    <td class="tg-031e" >Age: {{ $r->age }}</td>
    <td class="tg-031e" >Sex: <b>{{ substr($r->infant_gender,0,1) }}</b></td>
    <td class="tg-031e" >District:</td>
    <td class="tg-031e" ><b>{{ $r->district_name }}</b></td>
  </tr>


  <tr>
    <td class="tg-031e" >Infant ID No: </td>
    <td class="tg-031e" ><b>{{ $r->expID }}</b></td>
    <td class="tg-031e" >Health Center:</td>
    <td class="tg-031e" colspan="3"><b>{{ $r->facility_name }}</b></td>

  </tr>

  <tr>

    <td class="tg-031e" >EID Accession No: </td>
    <td class="tg-031e" ><b>{{ $r->axnNo }}</b></td>
    <td class="tg-031e" >Batch Number:</td>
    <td class="tg-031e" colspan="3"><b>{{ $r->batch_number }}</b></td>

  </tr>

  <tr>

    <td class="tg-031e" >Sample Collection Date: </td>
    <td class="tg-031e" ><b>{{ date_format(date_create($r->collected), "d-M-Y") }}</b></td>

    <td class="tg-031e" >Receipt Date:</td>
    <td class="tg-031e" ><b>{{ date_format(date_create($r->date_rcvd_by_cphl), "d-M-Y") }}</b></td>

    <td class="tg-031e" >Assay Date:</td>
    <td class="tg-031e" ><b>{{ date_format(date_create($r->assay_date ?: $r->date_results_entered ), "d-M-Y")  }}</b></td>

  </tr>
  <tr>
    <td colspan="6"><hr style="border: 2px solid black"></td>
  </tr>

  <?php $tNo = 0; $PCR_test_done = false; $SCD_test_done = false;?>

  @unless( empty($r->PCR_results_ReleasedBy) )
  <?php $PCR_test_done = true;  ?>

  @if( $r->PCR_result == 'SAMPLE_WAS_REJECTED')
    <tr>
      <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>HIV1-DNA-PCR</b></td>
      <td class="tg-031e" colspan="2">&nbsp;<br>Result:<br>
        <b>{{ str_replace("_", " ", $r->PCR_result) }}</b>
        <br>Rejection Reason: {{ display_comment($r) }}
      </td>
    </tr>
  @else
    <?php if($r->test != "PCR" && goToNextRow($r) ) {
            $j = $i + 1; // peep at next row, but stay on this row (NB: don't increment i)

            if(!empty($result[ $j ]))
              $u = $result[ $j ];   /* fetch data about 2nd signatory from next row */
            else
              $u = $result[ $i ];   /* there's no next row. Rare... */

          }else{
            $u = $result[$i];
          } 
    ?>
    <tr>
      <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>HIV1-DNA-PCR</b></td>
      <td class="tg-031e">Result:<br><b>{{ $r->PCR_result }}</b></td>
      <td class="tg-031e" colspan="3"   rowspan="2">
          Comment: &nbsp; {{ display_comment($r) }}<br>
          <img src="images/sewanyana.gif" style="width:70px; height:auto">
      </td>
    </tr>

      <tr>
        <td class="tg-031e"  >Reviewed By:</td>
        <td class="tg-031e">{{ strtoupper($u->family_name) }}, {{ $u->other_name }}</td>
      </tr>

  @endif

      
  @endunless

  @unless( empty($r->SCD_result) )
  <?php $SCD_test_done = true;?>

  @if( $r->SCD_result == 'SAMPLE_WAS_REJECTED')

  <tr>
    <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>Sickle Cell Test</b></td>
    <td class="tg-031e" colspan="2">
      Result:<br>
      <b>{{ str_replace("_", " ", $r->SCD_result) }}</b>
      <br>Rejection Reason: {{ display_comment($r) }}
    </td>
  </tr>
  
  @else


  <?php $u = $result[ $i ]; ?>
  <tr>
    <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>Sickle Cell Test</b></td>
    <td class="tg-031e">Result:<br><b>{{ $r->SCD_result == 'FAILED' ? 'INVALID' : $r->SCD_result }}</b></td>
    <td class="tg-031e" colspan="3"   rowspan="2">
        Comment: &nbsp; {{ display_comment($r) }}<br>
        <img src="images/sewanyana.gif" style="width:70px; height:auto">
    </td>
  </tr>

      <tr>
        <td class="tg-031e"   >Reviewed By:</td>
        <td class="tg-031e">{{ strtoupper($u->family_name) }}, {{ $u->other_name }}</td>
      </tr>
  
  @endif  


  @endunless


  <tr>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
  </tr>
  <tr>
    <td colspan="6">

      @if($PCR_test_done)
          
          <div style="float:left; width: 115px;"><b>HIV Medical Notes:</b></div>

          @if($r->PCR_result === "NEGATIVE")

            <div style="float:left; margin-left: 1em; margin-bottom: 0.3em;">
              1) A negative result implies an HIV free status <u>at the time of testing.</u> <br>
              2) Further exposure to HIV risks (for example through breastfeeding) may result in HIV infection.
            </div>

            <div style="clear:left;float:left;width: 115px;"><b>HIV Testing Protocol:</b></div>

            <DIV style="float:left; margin-left: 1em;">
              1) If this is the first test, this baby should be tested again 6 weeks after breastfeeding stops.<br>
              2) All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.
            </DIV>
          @endif

          @if($r->PCR_result === "POSITIVE")

            <div style="float:left; margin-left: 1em; margin-bottom: 0.3em;">
                  Action: Start treatment immediately
            </div>

            <div style="clear:left;float:left;width: 115px;"><b>HIV Testing Protocol:</b></div>

            <DIV style="float:left; margin-left: 1em;">
              1) Take off another sample on the day of initiation of treatment and send it.<br>
              2) All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.
            </DIV>
            
          @endif


          @if($r->PCR_result === "INVALID")


            <div style="float:left; margin-left: 1em; margin-bottom: 0.3em;">
                Invalid result can be caused by loss of specimen integrity due to<br> 
                contamination, Poor sample handling (poor drying, exposure to moisture and other adverse condition or
                target below detection limits.
            </div>

            <div style="clear:left;float:left;width: 115px;"><b>HIV Testing Protocol:</b></div>

            <div style="float:left; margin-left: 1em;">
              1) Take off another sample on the day of initiation of treatment and send it.<br>
              2) All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.
            </div>
          @endif

      @endif

      @if($SCD_test_done)
          

          @if($r->SCD_result === "NORMAL")
            <div style="clear:left; float: left; margin-top: 0.3em;">
                <b>Sickle Cell Medical Notes: </b>
                This patient has normal haemoglobin and DOES NOT have sickle cell trait or sickle cell disease
            </div>
          @elseif($r->SCD_result === "VARIANT")
            <div style="clear:left; float: left; margin-top: 0.3em;">
              <b>Sickle Cell Medical Notes: </b>          
              Variant: This patient is normal and does not have sickle cell trait or sickle cell disease.
            </div>
          @elseif($r->SCD_result === "CARRIER")
            <div style="clear:left; float: left; margin-top: 0.3em;">
                <b>Sickle Cell Medical Notes: </b>
                The patient is a sickle cell carrier, but does not have sickle cell disease and so 
                will not suffer any complications of sickle cell disease
            </div>
          @elseif($r->SCD_result === "SICKLER")
            <div style="clear:left; float: left; margin-top: 0.3em;">
                <b>Sickle Cell Medical Notes: </b>
                This patient has sickle cell disease. Sickle Cell Disease causes abnormally shaped red blood cells and 
                numerous health complications including infection and anaemia. The patient should receive the full series of pneumalcoccal
                vaccinations promptly and also penicillin prophylaxis until the age of 5 years
            </div>
          @elseif($r->SCD_result === "FAILED")
            <div style="clear:left; float: left; margin-top: 0.3em;">
                <b>Sickle Cell Medical Notes: </b>
                Invalid test due to poor sample integrity. A new sample should be sent
            </div>
          @endif

      @endif


    </td>
  </tr>
</table>
<footer></footer>

@endfor


</body>
</html>



<?php if(Request::has('pp')) 
        $print_me = "window.onload = function() { window.print() }";
      else
        $print_me = "";
?>  

<script type="text/javascript">
      {{ $print_me }}

// hb ss = scd = the patient has scd. scd causes abnormally shaped red blood cells and numerous health complications
// including infection and anaemia. The patient should receive the full series of pneumococcal vaccinations promptly
// and also penicillin prophylaxis until the age of 5 years.
// hb as = sct = The patient is a sickle cell carrier but does not have SCD. The patient will NOT have any health complications of SCD
// hb av = hb aa = normalHg = The patient has normal haemoglobin and does not have SCD or SCT
// p = INVALID: Test was inconclusive due to poor sample integrity. Please send a new sample.

</script>