@extends('layouts/layout')


@section('content2')

    <!-- Select2 -->
        <?php $web_server = env('WEB_HOST', ""); ?>
        <link   href="{{$web_server}}/css/release_sc.css" rel="stylesheet" />
        <link   href="{{$web_server}}/css/select2.min.css" rel="stylesheet" />
        <script src="{{$web_server}}/js/select2.min.js"></script>
        <script src="{{$web_server}}/js/plugins/notify.min.js"></script>


        <style type="text/css">
            label{ font-weight: normal;}
            .dbs_label {
                float:left; 
                width: 7em; 
                text-align: right; 
                margin-right: 1em;
            }
            .dbs_field {
                float:left; 
                width: 7em; 
                text-align:center;
            }

            #fixed-to-bottom {
                /* cX: still experimental */
               position: fixed;
                top:0;
                right: 0; 
            }       
            td{
                font-size: smaller;
            } 
             

            .age_label{
                width: 50px;
                float: left;
                margin-left: 10px;
                margin-right: 5px;
                text-align: right;
                clear: left;
            }
            .age_field{
                width: 50px;
                float: left;
            }

            .save_age{
                color: white;
                background-color: green;
            }

            .no_age{
                color: white;
                background-color: red;
            }

            #age_pop_up { 
                background-color:#fff;
                border-radius:15px;
                color:#000;
                display:none; 
                padding:20px;
                min-width:400px;
                min-height: 180px;
            }

            #date_pop_up { 
                background-color:#fff;
                border-radius:15px;
                color:#000;
                display:none; 
                padding:20px;
                min-width:400px;
                min-height: 180px;
            }
            .b-close{
                cursor:pointer;
                position:absolute;
                right:10px;
                top:5px;
            }

            .date_select{
                height: 2em;
                margin: 2px;
            }

            ::-webkit-datetime-edit { padding: 0.1em; }
            ::-webkit-datetime-edit-text { color: red; padding: 0 0.3em; }
            ::-webkit-inner-spin-button { display: none; }
            ::-webkit-calendar-picker-indicator { background: orange; }

            .date_link {text-decoration: none; }

        </style>

        <style type="text/css"> /* used on tests (PCR/EID) */

        select.test_to_do {
            -moz-appearance: window;
            -webkit-appearance: none;
            background: #fff right center no-repeat;
            padding-right: 20px;
        }

        option{
            text-align: center;
        }
        @-moz-document url-prefix() {
        .wrapper {
             background: #fff right center no-repeat;
             padding-right: 20px;
          }
        }
        </style>

    <link rel="stylesheet" href="{{$web_server}}/css/pikaday.css">
    <script src="{{$web_server}}/js/pikaday.js"></script>
    <script src="{{$web_server}}/js/plugins/pikaday.jquery.js"></script>

<div class="container">
    @include('quick_access_menu')
    <!-- <div id="xxy"   style="float:right;text-align:right; width:20em;"><a href="#" id="rscs">Release Sickle Cell samples</a></div> -->
    <!-- <div id="undo"  style="clear:both;float:right;"><a href="#" rc="" id="undo_link" style="color:brown;display:none">Undo</a></div> -->
    <div id="nRejects"></div>
</div>

<?php

    $sc = new EID\Http\Controllers\LabController;

    $SQL = "SELECT  
                dbs_samples.id AS sample_id, 
                dbs_samples.infant_name, 
                dbs_samples.infant_age, 
                dbs_samples.infant_dob,
                dbs_samples.infant_gender,
                dbs_samples.infant_exp_id, 
                dbs_samples.date_dbs_taken,
                dbs_samples.nSpots,
                dbs_samples.sample_rejected,
                dbs_samples.rejection_reason_id,
                dbs_samples.rejection_comments,
                dbs_samples.pos_in_batch,            
                dbs_samples.batch_id, 
                dbs_samples.PCR_test_requested,
                dbs_samples.SCD_test_requested,
                dbs_samples.ready_for_SCD_test,

                batches.id,
                batches.envelope_number, 
                batches.batch_number,
                batches.date_dispatched_from_facility,
                batches.date_rcvd_by_cphl,
                batches.facility_id AS facility_id, 

                facilities.id,
                facilities.facility AS facility_name,
                facilities.districtID,
       
                districts.id,
                districts.district,
                districts.regionID,

                regions.id,
                regions.region AS province_name, 
                regions.pprefix AS province_code 

        FROM    batches, dbs_samples, facilities, districts, regions 

    WHERE   batches.id = '$batch_id'
    AND     batches.facility_id = facilities.id
    AND     facilities.districtID = districts.id
    AND     districts.regionID = regions.id
    AND     dbs_samples.batch_id = batches.id

    ORDER BY    dbs_samples.batch_id, dbs_samples.pos_in_batch";


    $dbs = DB::select($SQL);

// dd($dbs);

    $nSamplesInBatch = count($dbs);

    if($nSamplesInBatch === 0) dd('That batch does NOT exist. Please go back');

    $sample = $dbs[0];

    $navList = "";
    $navOptions = "";
    $nSamples = count( $dbs);
    
    for ($i=0; $i < $nSamples; $i++) { 
        $sample = $dbs[ $i ];
        $navOptions .=  '<option value="' .  $i . '">' .
                            "" . (1+$i) . " : $sample->infant_name  (Exp ID: $sample->infant_exp_id)" . 
                        '</option>';
    }
    $navList = '<select id="vnav">' . $navOptions . '</select>';
    $sample = $dbs[ 0 ];
?>
<section id='s2' class='mm' style="clear:both"></section>
    <table align="center" style="margin-top: 0.5em">
        <tr>
            <td>
<?php       

                $SQL = "SELECT  batches.id AS batchID, batch_number, envelope_number, " .
                                "date_dispatched_from_facility, date_rcvd_by_cphl, " .
                                "facility_name, count(batch_id) as nSamples, " .
                                "SUM(PCR_test_requested='YES') as PCR_test " .

                            "FROM batches, dbs_samples " .
                                "WHERE batches.id = dbs_samples.batch_id " .
                                    "GROUP BY (batch_id) ".
                                            "ORDER BY batch_id DESC";


                $results = DB::select( $SQL );

                $nBatches = count($results);
                $envelope = "_NONE_";
?>

                <div style="width: 500px;">
                <select class="js-example-basic-single" style="width:225px; float:left;"
                                id="batch_selector" name="batch_selector" >
                        <option></option>
                    @for($i=0; $i < $nBatches; $i++)
                        <?php $batch = $results[ $i ];?>
                        <option value="{{ $batch->batchID }}">{{ $batch->batch_number  }}</option>
                    @endfor

                </select>

                <div style="width:120px; text-align: right; float: left; margin-top: 6px;">Go To Batch # :  </div>
                </div>

                <br>&nbsp;





                 
<?php       
                $SQL = "SELECT  id, infant_name, batch_id, pos_in_batch FROM dbs_samples WHERE in_workSheet = 'NO'";

                $dbs_samples = DB::select( $SQL );

                $nDBS = count($dbs_samples);


?>
                <div style="width: 500px;">
                <select class="js-example-basic-single" style="width:225px; float:left;"
                                id="dbs_selector" name="dbs_selector" >
                        <option></option>
                    @for($j=0; $j < $nDBS; $j++)
                        <?php $s = $dbs_samples[ $j ];?>
                        <option value="{{ $s->batch_id }}?p={{ $s->pos_in_batch }}">{{ $s->id  }} : {{ $s->infant_name  }}</option>
                    @endfor
                </select>
                <div style="width:120px; text-align: right; float: left; margin-top: 6px;">Go To Sample # : </div>
                </div>
                <br>&nbsp;


            </td>
        </tr>
        <tr>
            <td>
                <div style="width: 500px; text-align: center;">

                <a href="#" id="prev">&laquo; Prev</a> {!! $navList !!} <a href="#" id="next">Next &raquo; </a>
                </div>
            </td>
        </tr>
    </table>
    <table border=1 align="center" style="border: 1px solid #dedede; margin-top: 1em;">
        <tr>
            <td></td>
        </tr>
        <tr>
            <td style="color: white; background-color: #aaa; font-size: 3em;" 
                    colspan="2" align="center" contenteditable="true" id="infant_name">
                {{ $sample->infant_name }}
            </td>
        </tr>
        <tr>
            <td style="height:5em; padding: 2em;" align="center" >

                <div id="region">
                    <div    class="region_code" 
                            id="province_code" 
                            style="font-size:4em; font-weight:bold; ">
                        
                        {{ $sample->province_code }}
                    
                    </div>
                    <div id="province_name" style="font-size:1em; color: #aaa; font-size: 1.2em; ">
                        {{ $sample->province_name }}
                    </div>

                   <div style="color: #aaa;height: 1.2em; line-height: 1.2em; font-size: 1.2em;clear:both">
                        <div style="float:left;  width:5em;">Facility:</div>
                        <div id="facility_name" style="float:left;  display: inline-block; white-space: nowrap; margin-right: 1em;">
                            {{ $sample->facility_name }}
                        </div>
                   
                    <div style="color: #aaa;height: 1.2em; line-height: 1.2em; font-size: 1.2em; margin-top: 2em">
                        <div style="float:left; width:5em;">District:</div>
                        <div id="district" style="float:left;">{{ $sample->district }}</div>
                    </div>

                    
                    </div>                     
                </div>


            </td>
            <td style="padding: 3em;" rowspan="2">
                <div style="color: #aaa;height: 2em; line-height: 2em; font-size: 2em;">
                    <div class="dbs_label">Batch #:</div>
                    <div id="batch_number" class="dbs_field" contenteditable="true">{{ $sample->batch_number }}</div>
                </div>

                <div style="color: #aaa;height: 2em; line-height: 2em; font-size: 2em;">
                    <div class="dbs_label">EXP ID:</div>
                    <div id="infant_exp_id" style="height:1.8em;" class="dbs_field" contenteditable="true">{{ $sample->infant_exp_id }}</div>
                </div>

                <div style="color: #aaa;height: 2em; line-height: 2em; font-size: 2em;clear:both">
                    <div class="dbs_label">Accession #:</div>
                    <div class="dbs_field" id="sample_id" contenteditable="false">{{ $sample->sample_id }}</div>
                </div>

                <div style="color: #aaa;height: 2em; line-height: 2em; font-size: 2em;clear:both;">
                    <div class="dbs_label">Tests:</div>
                    <div class="dbs_field" style="width: 200px; margin-left: 10px;" >

                        <span class="wrapper">
                        <input type="hidden" id="ready_for_SCD_test" value="{{ $sample->ready_for_SCD_test}}">
                        <select class="test_to_do" style="font-size: 1em; border: none" id="requested_tests">
                            <option>PCR</option>
                            <option>SCD</option>
                            <option>PCR + SCD</option>
                        </select>
                        </span>
                        
                    </div>     
                    <img src="/EID.png" style="height: 2em; width:auto; margin-bottom: 100px;" id="eid_icon">
                    <img src="/SCD.png" style="height: 2em; width:auto; margin-bottom: 100px;" id="scd_icon">
                </div>
                    

            </td>
        </tr>
        <tr>
            <td align="center" style="background-color: #dedede">&uArr;<b>&nbsp;Region</b></td>
        </tr>



        <tr style="font-size: 1.5em;">
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa">
                Number of Spots:
            </td>
            <td style="height: 3em; text-align: left; padding-left: 0.5em; color: #aaa;">
            <div id="nSpots_div">
                {!! Form::radio('nSpots', '5', false, array("id"=>"five", "class"=>"sRadio")) !!}
                {!! Form::label('five', '5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', array("class"=>"sRadio")) !!}
                
                {!! Form::radio('nSpots', '4', false, array("id"=>"four", "class"=>"sRadio")) !!}
                {!! Form::label('four', '4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', array("class"=>"sRadio")) !!}

            
                {!! Form::radio('nSpots', '1', false, array("id"=>"one", "class"=>"sRadio") ) !!}
                {!! Form::label('one', '3 or less&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', array("class"=>"sRadio")) !!}

                {!! Form::radio('nSpots', 'Unknown', false, array("id"=>"Unknown", "class"=>"sRadio") ) !!}
                {!! Form::label('Unknown', 'Unknown', array("class"=>"sRadio")) !!}
            </div>
            </td>
        </tr>

        <tr style="font-size: 1.5em;">
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa; padding-top: 0.75em;  padding-bottom: 1.5em;">
                Verification Status:
            </td>
            <td style="min-width: 32em; height: 3em; text-align: left; padding-left: 0.5em; color: #aaa; padding-top: 0.75em;  padding-bottom: 1.5em;">

                <select id="sample_rejected" style="border: none; border-bottom: 1px dotted gray;">
                    <option value="NOT_YET_CHECKED"></option>
                    <option value="NO">Approved</option>
                    <option value="YES">Rejected</option>
                    <option value="Rejected FOR EID">Rejected for EID Only</option>
                </select>

                
                <span id="rejection_label" style="display: none;font-size: 0.9em; color: #FFA3A3;">&nbsp;&nbsp;<b>Reason:</b> </span>

                <?php
                $rjctn_rsn_styl="display: none; border:none; font-size: 0.9em; margin-right: 1em;border: none; border-bottom: 1px dotted gray; color: #FFA3A3;";
                echo Form::select("rejection_reason_id",[""=>""]+EID\Models\Appendix::appendicesArr2(6,false),"",['id'=>'rejection_reason','style'=>$rjctn_rsn_styl]);
                 ?>

                <div>
                    <input type="text" style="display:none;float: left;font-size:smaller; margin-top: 0.5em;border: none; color: brown" readonly="yes" value="No Age = No EID test" />
                    <input type="text" id="reason_other" name="reason_other" value="" 
                            style="float: left;font-size:smaller; width: 22em; border: none; margin-top: 0.5em;margin-left: 4em; margin-right: 1em;" />
                </div>
            </td>
        </tr>


        <tr>
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa">Date Sample was Collected:</td>
            <td style="height: 3em; text-align: left; padding-left: 0.5em; color: #aaa" >
                <a href="#" class="date_link" id="date_dbs_taken"> {{ $sample->date_dbs_taken }} </a>
            </td>
        </tr>

        <tr>
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa">
                Date dispatched to lab:
            </td>
            <td style="height: 3em; text-align: left; padding-left: 0.5em; color: #aaa">
                <a href="#" class="date_link" id="date_dispatched_from_facility"> {{ $sample->date_dispatched_from_facility }} </a>

            </td>
        </tr>
        <tr>
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa">
                Date Received at Lab:
            </td>
            <td style="height: 3em; text-align: left; padding-left: 0.5em; color: #aaa">
                
                <a href="#" class="date_link" id="date_rcvd_by_cphl"> {{ $sample->date_rcvd_by_cphl }} </a>
            </td>
        </tr>
        <tr>
            <td style="height: 3em; text-align: right; padding-right: 0.5em; color: #aaa">
                <a href="/dbsQ/{{ $batch_id }}" id="unlock" style="color: #FF9933;">View This Batch&nbsp;</a>

                    <!-- Date-editing pop up -->
                    <div id="date_pop_up">
                        <a class="b-close">x</a>
                        
                        <form>
                        <table align="center">

                            <tr>
                                <th colspan="2" style="text-align: center">Edit Dates</th>
                            </tr>
                            <tr>
                                <td>Date collected:</td>
                                <td><input type="date" class="date_select" id="new_date_dbs_taken"></td>
                            </tr>
                            <tr>
                                <td>Date dispatched:</td>
                                <td><input type="date"  class="date_select" id="new_date_dispatched_from_facility"></td>
                            </tr>
                            <tr>
                                <td>Date received:</td>
                                <td><input type="date"  class="date_select" id="new_date_rcvd_by_cphl"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button id="save_dates" style="float:right">SAVE</button></td>
                            </tr>                            
                        </table>
                        </form>
                    </div>


                    <!-- Age-editing pop up -->
                    <div id="age_pop_up">
                        <a class="b-close">x</a>
                        
                        <form>
                        <table align="center">

                            <tr>
                                <td colspan="2" style="font-size: 15px; font-weight:bold;text-align: center; ">
                                    <span style="color: #44a" id="kid_name">{{$sample->infant_name}}</span><br>

                                    <u>What's the Age?</u><br>
                                    <label for="infant_age_" class="age_label">Age:</label>
                                    <input type="text" class="ageFmt" name="infant_age_" id="infant_age_" value=""><br>

                                    <label for="infant_dob_" class="age_label">Born:</label>
                                    <input type="text" name="infant_dob_" id="infant_dob_" value="" readonly="yes">
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="width:175px;"><button id="no_age" name="no_age" class="btn btn-danger">No Age = No EID test</button></td>
                                <td align="right" style="width:175px"><button id="save_age" name="save_age" class="btn btn-primary">SAVE AGE</button></td>
                            </tr>
                        </table>
                        </form>
                    </div>
        
            </td>
            <td style="padding:0px;">
                            
                <div style="width: 50%; float:left; margin: 0px; line-height: 3em;">
                    <a href="/samples/{{ $batch_id }}" id="edit_batch" style="color: #FF9933;  float: left ">&nbsp;&nbsp;Edit This Batch</a>
                </div>
                <div style="width: 50%; float:left; margin: 0px; ">
                    <button style="height: 3em; float: right;" id="save_dbs">Save & Load next Sample</button>
                </div>

                
            </td>
        </tr>

    </table>
    <div style="height:2em;">
        &nbsp;<div id="rc" style="display:none">{{ $sc->generateNewReleaseCode() }}</div>
    </div>

    <script src="/js/moment.js" type="text/javascript"></script>
    <script src="/js/plugins/bpopup.jquery.min.js" type="text/javascript"></script>
    <script src="/js/approve.js" type="text/javascript"></script>
    <script src="/js/ageFmt.js" type="text/javascript"></script>
    <script type="text/javascript">
        var APPROVAL_MODULE = {};
            APPROVAL_MODULE.activeSample = 0;
            APPROVAL_MODULE.dbs = {!! json_encode($dbs) !!};
            APPROVAL_MODULE.current_user = {{ \Auth::user()->id }};
            APPROVAL_MODULE.selectFirstSample = function (){
                var p = {{ \Request::get('p', 1) }};// p = position in batch
                    p = p - 1;  // -1: adjust for zero-based array
                return p;
            }
            APPROVAL_MODULE.dbs_collection_date = function(sample) {

                var dbs_date, dbs_date_changed, possible_formats, ymd_format;

                sample = sample || APPROVAL_MODULE.dbs[ APPROVAL_MODULE.activeSample ] ;
                dbs_date_changed = sample.date_dbs_taken != $("#date_dbs_taken").val().trim() && 
                                        ( $("#date_dbs_taken").val().trim().length > 0 );// and new value is not empty


            console.log('sample.date_dbs_taken = ' + sample.date_dbs_taken);
            console.log('dbs_date_changed = ' +  dbs_date_changed );


                dbs_date = dbs_date_changed ? $("#date_dbs_taken").val().trim() : sample.date_dbs_taken;

            console.log('dbs_date:');
            console.log(dbs_date);

                // approval_data.date_dbs_taken = ymd_format;
                possible_formats = ["YYYY-MM-DD", "Do MMM YYYY"];
                ymd_format = moment(dbs_date, possible_formats).format("YYYY-MM-DD");

            console.log('ymd_format:');
            console.log(ymd_format);

                return ymd_format;
            };            
    </script>
    <script src="/js/odiff.umd.js"></script>
    <script src="/js/edit_batches.js"></script>
    <script type="text/javascript">
        @if( $sample->sample_rejected == "NOT_YET_CHECKED" )
            DBS.IGNORE_EVENTS = false;/* new batch: record data entrant's speed */
        @else
            DBS.IGNORE_EVENTS = true;/* existing batch: ignored */
        @endif
    </script>

@stop
