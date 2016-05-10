@extends('layouts/layout')

@section('content')
    <?php $web_server = env('WEB_HOST', "http://localhost"); ?>
    <?php $css_modifier = Request::has('fd') ? 'inv': ' '; /* fd = for dispatch */ ?>
    <?php $legend_style = Request::has('fd') ? 'show_legend': 'hide_legend'; ?>
    <?php $show_top_legend = Request::has('t') ? true: false; ?>
    <?php $show_bottom_legend = Request::has('b') ? true: false; ?>

<?php 

function selectIf($value, $field)
{
  if($field == $value)
    return ' selected="YES" ';
  else
    return '';
}

function sr($result)
{
  if($result == 'POSITIVE') return '+';
  if($result == 'NEGATIVE') return '-';

  return '??';

}
?>
<?php $print_for_dispatch = Request::has('fd');

    $f = Request::get('f') ?: 0;
    $filter = "batches.id IN ('$f')";
    $sql = "SELECT  dbs_samples.id AS axnNo, 
                    infant_name, 
                    batches.id as b_id,
                    dbs_samples.batch_id,
                    infant_contact_phone,
                    infant_exp_id AS expID, 
                    date_dbs_taken AS collected, 
                    infant_age AS age, 
                    facility_id AS HC, 
                    batch_id, 
                    batch_number,
                    date_rcvd_at_facility,
                    date_rcvd_at_facility AS rcpt_date, 
                    f_ART_initiated, 
                    f_infant_referred, 
                    f_results_rcvd_at_facility, 
                    f_results_collected_by_caregiver,
                    f_reason_ART_not_initated,
                    f_date_dispatched_from_facility,
                    f_date_rcvd_by_cphl,
                    f_senders_name,
                    f_senders_telephone,
                    f_date_results_collected,
                    f_date_ART_initiated,

                    batches.facility_id, 
                    batches.facility_district, 
                    date_dispatched_to_facility,
                    batches.date_dispatched_to_facility AS assay_date, 
                    accepted_result AS result,
                    facilities.facility AS facility_name, 
                    districts.name AS district_name,
                    facilities.hubID,
                    hubs.hub 


          FROM batches, dbs_samples, districts, facilities, hubs

            WHERE   $filter
              AND   accepted_result  = 'POSITIVE'
              AND   batches.facility_id = facilities.id 
              AND   batches.id = dbs_samples.batch_id
              AND   facilities.districtID = districts.id
              AND   facilities.hubID = hubs.id  ";        

// 
// dd($sql);

    $result = DB::select($sql);

// dd($result);

    $nRows = count($result);
    if($nRows == 0) dd('This facility has no infants for follow-up. Please go back');
    $i = 0;

?>

   

    <!-- Select2 -->
    <script src="{{$web_server}}/js/jquery11.min.js"></script>
    <link   href="{{$web_server}}/css/select2.min.css" rel="stylesheet" />
    <script src="{{$web_server}}/js/select2.min.js"></script>


    <script src="{{$web_server}}/js/moment.js"></script>
    <script src="{{$web_server}}/js/md5.js"></script>

    <!-- First load pikaday.js and then its jQuery plugin -->
     <script src="js/pikaday.js"></script>
    <script src="js/plugins/pikaday.jquery.js"></script>
    <link rel="stylesheet" href="css/pikaday.css">

    <!-- JavaScript to handle validation + display of infant age -->
    <script src="{{$web_server}}/js/ageFmt.js"></script>




<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}

.tg td{font-family:Arial, sans-serif;font-size:12px;padding:10px 5px;border-style:none;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;border-style: none;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-6f4q{font-weight:bold;font-size:12px;font-family:Arial, Helvetica, sans-serif !important;;text-align:center}


.tg .tg-s6z2{text-align:center}
.tg .tg-ypuk{font-weight:normal; background: #eee; color: black; border: 1px solid #ddd; font-size:12px;font-family:Arial, Helvetica, sans-serif !important;;text-align:center}
.tg .tg-pz9v{font-size:12px; border: 1px solid #ddd; font-family:Arial, Helvetica, sans-serif !important;;text-align:center}


.nt td { border: none; }


    td .prompt { text-align: right;}
  td input.xl  {
      border: none;
      border-bottom: 1px dotted gray;            
  }
  td span.xl  {
      border: none;
      border-bottom: 1px dotted gray; 
      font-size: larger;   
  }
  td select.xl  {
      border: none;
      border-bottom: 1px dotted gray;    
  }


  td select.xl.inv  {
      display: none;
  }
  td input.xl.inv  {
      display: none;
  }

   th.bordered { border: 1px solid #ddd;}
   td.bordered { border: 1px solid #ddd;}

   td input.datepicker{
        font-size: 12px;
        width: 8em;
        text-align: center;
    }

    #show_legend{}
    #hide_legend{
        display: none;
    }
    
    .dispatch_label{   
        width:15em; 
        float: left;
        clear: left;        
        padding: 4px; 
        text-align: right;
    }

    .dtf{/* dispatch text field */

        width:125px;
    }


/*    @media print {
        @page {
            size: A4 potrait;
            margin: 0.1cm 0.1cm 0.1cm 0.1cm;
        }
    }
*/

#main_table{
    background-image: url('{{$web_server}}/images/coat-of-arms.jpg');
    background-size: 100px auto;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: 1% 0%;
}

</style>

<style type="text/css" media="print">
  body { margin:0px; }
  .tg td{font-family:Arial, sans-serif;font-size:12px;padding:10px 5px;border-style:none;border-width:1px;overflow:hidden;word-break:normal;}
  .tg th{font-family:Arial, sans-serif;font-size:12px;font-weight:normal;padding:10px 5px;border-style: none;border-width:1px;overflow:hidden;word-break:normal;}
  .tg .tg-6f4q{font-weight:bold;font-size:12px;font-family:Arial, Helvetica, sans-serif !important;;text-align:center}


  .tg .tg-s6z2{text-align:center}
  .tg .tg-ypuk{font-weight:normal; background: #eee; color: black; border: 1px solid #ddd; font-size:12px;font-family:Arial, Helvetica, sans-serif !important;;text-align:center}
  .tg .tg-pz9v{font-size:12px; border: 1px solid #ddd; font-family:Arial, Helvetica, sans-serif !important;;text-align:center}

</style>

@if($nRows > 0)
<?php   $f = $result[0]; ?>

{!! Form::open(array('url' => '/artDB')) !!}
<table  class="tg" id="main_table">
  <tr>
      <th >
          &nbsp;
      </th>
      <th colspan="12" align="center">
            <div style="font-size: 1.2em"><b>HIV Positive Infant - ART Initiation Follow-Up</b></div>
            <span>Please complete this form and return it to CPHL with your next batch of DBS samples</span>
      </th>
  </tr>
  <tr>
    <td colspan="8" valign="top">
        <table class="nt" id="nx">
            <tr >
              <td nowrap="yes" class="prompt">Health Facility:</td>
              <td colspan="3">
                  <span class="xl" style="width: 100%" readonly="yes"> 
                      {{ $f->facility_name }} &nbsp;&nbsp; (in {{ $f->district_name }} district) 
                    </span>
              </td>
            </tr>
            <tr>
              <td nowrap="yes"  class="prompt">Does this facility offer paediatric ART?</td>
              <td>
                    @if($print_for_dispatch)
                        <input class="xl" size="5" readonly="yes" />
                    @else
                        {!! Form::select("f_paediatricART_available", array("YES"=> "YES", "NO" => "NO"), null,
                                    array("class"=>"xl", "id"=>"f_paediatricART_available_$i")) !!}
                    @endif
              </td>
              <td class="prompt" colspan="2" nowrap="yes">Hub: 
                <input class="xl" readonly="yes" style="width:18em;text-align: center" value="{{$f->hub}}" />

              </td>
            </tr>
            <tr>
              <td nowrap="yes"  class="prompt">Form Filled By (Your Name):</td>
              <td>

                @if($print_for_dispatch) 
                  <input class="xl" readonly="yes" />
                @else

                  <input class="xl" id="f_senders_name" name="f_senders_name" value="{{ $f->f_senders_name }}" />
                @endif

              </td>
              <td nowrap="yes" class="prompt">Your Phone No.:</td>
              <td>
                @if($print_for_dispatch) 
                  <input class="xl" readonly="yes" />
                @else
                  <input class="xl" id="f_senders_telephone" name="f_senders_telephone" value="{{ $f->f_senders_telephone }}"/>
                @endif

              </td>
            </tr>
            <tr style="display:none">
              <td nowrap="yes"  class="prompt nt">Implementing Partner:</td>
              <td colspan="3">

                @if($print_for_dispatch) 
                  <input class="xl" style="width: 100%" readonly="yes" />
                @else
                  <input class="xl" style="width: 100%"/>
                @endif

              </td>
            </tr>

          </table>
    </td>
    <td colspan="5" valign="top">
        <div style="border: 1px solid #eee">

            <label class="dispatch_label">Form Reference No:</label>
              <input  class="xl" value="{{ $f->batch_number }}" readonly="yes" 
                      style="font-weight: bold; font-size:1.4em; width:125px; font-family: monospace" /><br/><br/>
        

            <label class="dispatch_label">Date Printed at CPHL:</label>
                @if($print_for_dispatch) 
                  <input class="xl dtf" value="{{ date('F jS, Y') }}" readonly="yes" />
                @else
                  <input class="datepicker xl dtf"   value="{{ $f->date_dispatched_to_facility }}"
                          id="date_dispatched_to_facility" name="date_dispatched_to_facility" style="width:125px; font-family: monospace" />
                @endif  <br/><br/> 

          
            <label class="dispatch_label">Date Received at Facility:</label>
                @if($print_for_dispatch) 
                  <input class="xl dtf" readonly="yes" />
                @else
                  <input class="datepicker xl dtf" value="{{ $f->date_rcvd_at_facility }}" 
                          id="date_rcvd_at_facility" name="date_rcvd_at_facility" style="width:125px; font-family: monospace"/>
                @endif  <br/><br/>  

            
            <label class="dispatch_label">Dispatch Date from Facility:</label>
                @if($print_for_dispatch) 
                  <input class="xl dtf" readonly="yes" />
                @else
                  <input class="datepicker xl dtf" value="{{ $f->f_date_dispatched_from_facility }}"  
                          id="f_date_dispatched_from_facility" name="f_date_dispatched_from_facility" style="width:125px; font-family: monospace" />
                @endif  <br/> <br/>


           
            <label class="dispatch_label">Date Received at CPHL:</label>
                @if($print_for_dispatch) 
                <input class="xl dtf" readonly="yes" />
                @else
                  <input class="datepicker xl dtf"  value="{{ $f->f_date_rcvd_by_cphl }}" 
                          id="f_date_rcvd_by_cphl" name="f_date_rcvd_by_cphl"  style="width:125px; font-family: monospace"/>
                @endif     </div>
    </td>
  </tr>
  <tr>
    <th class="tg-6f4q bordered" colspan="4">Infant Details</th>
    <th class="tg-ypuk" rowspan="2" ><br>Were EID <br>Results <br>Received <br>at Facility? <br>   (Y/N)</th>
    <th class="tg-6f4q bordered" colspan="2">Did caregiver return for results?</th>
    <th class="tg-6f4q bordered" colspan="4">Was the child initiated on ART <br>at this health facility?</th>
    <th class="tg-6f4q bordered" colspan="2">Has the child been<br>referred to another<br>Health Facility?</th>
  </tr>
  <tr>
    <td class="tg-ypuk">Exp No.</td>
    <td class="tg-ypuk">Name</td>
    <td class="tg-ypuk">Batch No.</td>
    <td class="tg-ypuk">Caregiver's<br>Telephone</td>
    <td class="tg-ypuk">Yes/No?<br><br></td>
    <td class="tg-ypuk">If Yes,<br>on what date?</td>
    <td class="tg-ypuk">Yes/No?</td>
    <td class="tg-ypuk">If Yes,<br>on what date?</td>
    <td class="tg-ypuk">If Yes,<br>State<br>ART No.</td>
    <td class="tg-ypuk">If No, Why?<br><b style="font-size: x-small">(Choose number below)</b></td>
    <td class="tg-ypuk">Yes/No?</td>
    <td class="tg-ypuk">If Yes,<br>which one?</td>
  </tr>
@if($show_top_legend)
  <tr>
    <td class="tg-031e bordered" colspan="13">
        <div style="font-size: smaller; background: #ddd;">
        <b>Reasons why child was not initiated on ART treatment:</b><br>

      <?php 
      
                $SQL3 = "SELECT * FROM appendices WHERE categoryID = 7 ORDER BY id";

                $result3 = DB::select( $SQL3 );

          ?>
                
                       @foreach($result3 as $g)
                                         
                  [{{ $g->code}} &nbsp;&nbsp;&nbsp;:{{ $g->appendix}}]
                   @endforeach

        </div>
    </td>
  </tr>
@endif
  <tr>
    <td class="tg-031e bordered" colspan="13"><b>Infants that require ART initiation from this cycle:</b></td>
  </tr>

<input type="hidden" value="{{ $nRows }}" name="nRows" />
<input type="hidden" value="{{ $result[0]->batch_id }}" name="batch_id" />
<input type="hidden" value="{{ $result[0]->b_id }}" name="b_id" />
<input type="hidden" value="{{ $result[0]->batch_number }}" name="batch_num" />
@foreach($result as $r)

  <tr>
  <?php $i++; ?>
  <input type="hidden" value="{{ $r->axnNo }}" name="sample_id[]" />

    <td class="tg-pz9v">
      <input class="xl" style="width: 4em; text-align: center" value="{{ $r->expID }}" name="expID[]" readonly="yes"/>
    </td>
    <td class="tg-pz9v">
      <input class="xl" style="width: 10em; text-align: center" 
              value="{{ ucwords(strtolower($r->infant_name)) }}({{sr($r->result)}})" name="infant_name[]" readonly="yes" />
    </td>

    <td class="tg-pz9v">
      <input class="xl" style="width: 5em; text-align: center" 
              value="{{ $r->batch_number }}" readonly="yes"  name="batch_number[]" />
    </td>

    <td class="tg-pz9v">
      <?php $phone = str_replace('+256', '0', ($r->infant_contact_phone) ); ?>
      <?php $phone = str_replace(' ', '', $phone ); ?>

      <input class="xl" style="width: 7em; text-align: center;"  readonly="yes" 
              value="{{ $phone }}"  name="infant_contact_phone[]"/>
    </td>
    <td class="tg-pz9v">

                  @if($print_for_dispatch)
                    &nbsp;
                  @else

                    <select class="xl {{$css_modifier}}" name="f_results_rcvd_at_facility[]">
                        <option></option>
                        <option {{ selectIf("YES", $f->f_results_rcvd_at_facility) }}>YES</option>
                        <option {{ selectIf("NO", $f->f_results_rcvd_at_facility) }}>NO</option>
                    </select>
                  @endif

    </td>
    <td class="tg-pz9v">

                  @if($print_for_dispatch)
                    &nbsp;
                  @else

                    <select class="xl {{$css_modifier}}" name="f_results_collected_by_caregiver[]">
                        <option></option>
                        <option {{ selectIf("YES", $f->f_results_collected_by_caregiver) }}>YES</option>
                        <option {{ selectIf("NO", $f->f_results_collected_by_caregiver) }}>NO</option>
                    </select></td>
                  @endif
    </td>
    <td class="tg-pz9v">

              @if($print_for_dispatch)
                    &nbsp;
                  @else
                {!! Form::text('f_date_results_collected[]', $f->f_date_results_collected, 
                            array("class"=>"datepicker xl $css_modifier") ) !!}
                  @endif


    </td>
    <td class="tg-pz9v">


                  @if($print_for_dispatch)
                    &nbsp;
                  @else

                    <select class="xl {{$css_modifier}}"  name="f_ART_initiated[]">
                        <option></option>
                        <option {{ selectIf("YES", $f->f_ART_initiated) }}>YES</option>
                        <option {{ selectIf("NO", $f->f_ART_initiated) }}>NO</option>
                    </select>

                  @endif

    </td>
    <td class="tg-pz9v">

              @if($print_for_dispatch)
                    &nbsp;
              @else
                {!! Form::text('f_date_ART_initiated[]', $f->f_date_ART_initiated, 
                            array("class"=>"datepicker xl $css_modifier") ) !!}
                  @endif

    </td>
    <td class="tg-pz9v">
      <input class="xl {{$css_modifier}}" 
              style="width: 4em; text-align: center" 
                value="{{$f->f_date_ART_initiated}}" name="f_ART_number[]"/>
    </td>
    <td class="tg-pz9v">
      <?php 
      
                $SQL2 = "SELECT * FROM appendices WHERE categoryID = 7 ORDER BY id";

                $result2 = DB::select( $SQL2 );

          ?>
                <select style="width:10em;" id="f_reason_ART_not_initated" name="f_reason_ART_not_initated[]" >
                        <option></option>
                       @foreach($result2 as $g)
                                         
                   <option value="{{ $g->code}}">{{ $g->code}}&nbsp;:{{ $g->appendix}}</option>
                   @endforeach
                </select>  

    </td>
    <td class="tg-pz9v">

                  @if($print_for_dispatch)
                    
                    &nbsp;
                  
                  @else
    
                    <select class="xl {{$css_modifier}}" name="f_infant_referred[]">
                        <option></option>      
                        <option {{ selectIf("YES", $f->f_infant_referred) }}>YES</option>
                        <option {{ selectIf("NO", $f->f_infant_referred) }}>NO</option>
                    </select>

                  @endif

    </td>
    <td class="tg-pz9v">
      
      @if($print_for_dispatch)
        <div style="width: 7em;">&nbsp;<!-- this space is for data entry --></div>
      @else

<?php 
      
                $SQL = "SELECT facilitys.id AS facility_id, facilitys.name AS facility_name , " .
                                "districts.name AS district, districtcode " .
                            "FROM facilitys, districts " .
                                "WHERE facilitys.district = districts.id " .
                                "ORDER BY district, facility_name";

                $results = DB::select( $SQL );
                $nFacilities = count($results);
                $district = "_NONE_";
?>

                <select class="js-example-basic-single xl {{$css_modifier}}" style="width:10em;" 
                                id="facility_selector" name="f_facility_referred_to[]" >
                        <option></option>
                    @for($i=0; $i < $nFacilities; $i++)
                        <?php $facility = $results[ $i ] ?>

                            @if( $district !== $facility->district )
                                
                                @if( $district !== "_NONE_" )
                                    </optgroup>
                                @endif

                                {{ $district =  $facility->district }}
                                <optgroup label="{{ $district }}">
                            @endif
                        <?php   

                            $v = json_encode($facility);
                            $this_facility_id = empty($batch)? "" : $batch->getFacilityID();
                            $selected = "";

                            if($facility->facility_id === $this_facility_id){
                                $selected = ' selected="YES" ';
                            } 
                        ?>
                        <option value="{{ $v }}" {{ $selected }}>{{ $facility->facility_name }}</option>
                    @endfor



                </select>
            
          @endif

    </td>
  </tr>

  @endforeach
  @if($show_bottom_legend)
    <tr id="">
      <td class="tg-031e bordered" colspan="13" bgcolor="#ddd">
          <div style="font-size: smaller; background: #ddd; ">
          <b>Reasons why child was not initiated on ART treatment:</b><br>
          <?php 
      
                $SQL4 = "SELECT * FROM appendices WHERE categoryID = 7 ORDER BY id";

                $result4 = DB::select( $SQL4 );

          ?>
                
                       @foreach($result4 as $g)
                                         
                  [{{ $g->code}} &nbsp;&nbsp;&nbsp;:{{ $g->appendix}}]
                   @endforeach

          </div>
      </td>
    </tr>
  @endif

  <tr>
    <td class="tg-031e bordered" colspan="13">There are no infants requiring follow-up from previous forms sent</td>
  </tr>
  <tr>
    <td class="tg-031e bordered" colspan="13">There are no infants that have been referred to you for ART initiation</td>
  </tr>
</table>

@endif

@if(! $print_for_dispatch)
  <input type="submit" name="x" value="Save Follow-Up form" style="float: right" />
@endif


{!! Form::close() !!}

        @if (Session::has('flash_message'))
            <div class="form-group">
                <p style="color: red">{!! Session::get('flash_message') !!}</p>
            </div>
        @endif

<p>&nbsp;</p>


    <?php if(Request::has('pp')) 
            $print_me = "window.onload = function() { window.print() }";
          else
            $print_me = "";
    ?>  

    <script type="text/javascript">
          {{ $print_me }}
    </script>


    <script type="text/javascript">

        $(function(){

            (function format_dates(){
            
                var formatted_date;
                var dateFields = $(".datepicker");
                var nDateFields = dateFields.length;
                var current_dateField;


                for(var i=0; i<nDateFields; i++){
                    current_dateField = dateFields[i];

                    if(current_dateField.defaultValue === "") continue;
                    formatted_date = moment(current_dateField.defaultValue).format("Do MMM YYYY");
                    current_dateField.value = formatted_date;
                }                
            })();

        });


        $('.datepicker').pikaday({
            firstDay: 1,
            minDate: new Date('2001-03-01'),
            maxDate: new Date('2020-04-01'),
            format: 'Do MMM YYYY'
        });


        $(".js-example-basic-single").select2();
        
    </script>
@stop