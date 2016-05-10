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

  $REJECTION_REASONS = "6";

  $sql = "SELECT  DISTINCT

                dbs_samples.id as axnNo, 

                  sample_rejected,
                  infant_name, infant_gender,
                  infant_exp_id as expID,
                  date_dbs_taken as collected,
                  infant_age as age,
                  facility_id as HC,
                  batch_number,

                  date_rcvd_by_cphl,

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
            AND   (PCR_results_ReleasedBy = 57 || SCD_results_ReleasedBy = 57)
            AND   batches.id = dbs_samples.batch_id 
            AND   batches.facility_id = facilities.id 
            AND   facilities.districtID = districts.id 
            AND   users.id = dbs_samples.sample_verified_by 
            AND   categoryID = $REJECTION_REASONS 
            AND   rejection_reason_id = appendices.id

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


?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

@for($i=0; $i < $nResults; $i++)
<?php $r = $result[$i]; ?>

<div style="height: 9em; border: 1px solid white">&nbsp;</div>
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
    <td class="tg-031e" ><b>{{ $r->assay_date ? date_format(date_create($r->date_rcvd_by_cphl), "d-M-Y") : date('d-M-Y')  }}</b></td>


  </tr>
  <tr>
    <td colspan="6"><hr style="border: 2px solid black"></td>
  </tr>

  <?php $tNo = 0; $PCR_test_done = false; $SCD_test_done = false; ?>

  @unless( empty($r->PCR_results_ReleasedBy) )
  <?php $PCR_test_done = true;?>
  <tr>
    <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>HIV1-DNA-PCR</b></td>
    <td class="tg-031e">Result:<br><b>{{ $r->PCR_result }}</b></td>
    <td class="tg-031e" colspan="3">Comment: &nbsp; {{ $r->rejection_reason_str }}</td>
  </tr>

      @unless(empty($r->PCR_results_ReleasedBy))
      <tr>
        <td class="tg-031e">Reviewed By:</td>
        <td class="tg-031e">{{ strtoupper($r->family_name) }}, {{ $r->other_name }}</td>
        <td class="tg-031e" colspan="3"> <img src="images/sewanyana.gif" style="width:120px; height:auto"></td>

      </tr>
      @endunless
      
  @endunless

  @unless( empty($r->SCD_results_ReleasedBy) )
  <?php $SCD_test_done = true;?>

  <tr>
    <td class="tg-031e">Lab Test # {{ ++$tNo }}:<br><b>Sickle Cell Test</b></td>
    <td class="tg-031e">Result:<br><b>{{ $r->SCD_result }}</b></td>
    <td class="tg-031e" colspan="3">Comment: &nbsp; {{ $r->rejection_reason_str }} </td>
  </tr>

      @unless(empty($r->SCD_results_ReleasedBy))
      <tr>
        <?php if(goToNextRow($r)) {$i++; $r = $result[$i];} /* go to next row and get data about 2nd signatory */ ?>
        <td class="tg-031e">Reviewed By:</td>
        <td class="tg-031e">{{ strtoupper($r->family_name) }}, {{ $r->other_name }}</td>

        <td class="tg-031e" colspan="3"><img src="images/sewanyana.gif" style="width:120px; height:auto"></td>
      </tr>
      @endunless

  @endunless


  <tr>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
  </tr>
  <tr>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
    <td class="tg-031e"></td>
  </tr>
  <tr>
    <td colspan="5">
      <b>Medical Notes</b><br>
  

      @if($PCR_test_done)
          @if($r->PCR_result === "NEGATIVE")
            <li>A negative result implies an HIV free status <u>at the time of testing.</u> <br>
            <li>Further exposure to HIV risks (for example through breastfeeding) may result in HIV infection.
            <br><br>

            <b>Testing Protocol:</b><br>
            <li>If this is the first test, this baby should be tested again 6 weeks after breastfeeding stops.
            <li>All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.</li>
          @endif

          @if($r->PCR_result === "POSITIVE")
            Action: Start treatment immediately
            <br><br>
            
            <b>Testing Protocol:</b><br>
            <li>Take off another sample on the day of initiation of treatment and send it.</li>
            <li>All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.</li>
          @endif


          @if($r->PCR_result === "INVALID")
            Invalid result can be caused by loss of specimen integrity due to<br> 
            <li>contamination, 
            <li>Poor sample handling (poor drying, exposure to moisture 
            and other adverse condition or
            <li>target below detection limits. 
            <br><br>

            <b>Testing Protocol:</b><br>
            <li>Take off another DBS sample and send it back to the lab.</li>
            <li>All children should be re-tested with a rapid test at 18 months of age irrespective of earlier PCR results.</li>
            
          @endif
      @endif

      @if($SCD_test_done)

          @if($r->SCD_result === "INVALID")
            The Sickle Cell Test was inconclusive due to poor sample integrity. <br>
            Please send a new sample.            
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

// hb ss = scd = the child has scd. scd causes abnormally shaped red blood cells and numerous health complications
// including infection and anaemia. The child should receive the full series of pneumococcal vaccinations promptly
// and also penicillin prophylaxis until the age of 5 years.
// hb as = sct = The child is a sickle cell carrier but does not have SCD. The child will NOT have any health complications of SCD
// hb av = hb aa = normalHg = The child has normal haemoglobin and does not have SCD or SCT
// p = INVALID: Test was inconclusive due to poor sample integrity. Please send a new sample.

// Life is a Highway by Tony Cochran
// https://www.facebook.com/IamSouthAfrican/videos/840977289283837/
</script>