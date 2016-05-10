@extends('layouts/layout')

@section('content')


    <style type="text/css">
 
        th,td{
            border:1px solid #ddd;
            padding: 0        
        }

        body, td {
            font-family: 'Segoe UI', arial;  
        }

        .td_col{
            text-align: center; 
            padding:0.3em
        }
 

    </style>


  </head>

<section id='s2' class='mm'></section>
@include('quick_access_menu')
<center>
<table>
<tr><td><a href="/dbs/approvedbatches">Approved Samples</a></td><td>&nbsp;</td> <td><a href="/dbs/rejectedbatches">Rejected Samples</a></td> <td>&nbsp;</td>    </tr>
</table>
<?php       

                $SQL = "SELECT  batches.id AS batchID, batch_number, envelope_number, " .
                                "date_dispatched_from_facility, date_rcvd_by_cphl, " .
                                "facility_name, count(batch_id) as nSamples, " .
                                "SUM(PCR_test_requested='YES') as PCR_test " .

                            "FROM batches, dbs_samples " .
                                "WHERE batches.id = dbs_samples.batch_id " .
                                 "   AND    dbs_samples.sample_rejected = 'NO' " .
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
                $SQL = "SELECT  id, infant_name, batch_id, pos_in_batch,sample_rejected FROM dbs_samples WHERE in_workSheet = 'NO' AND sample_rejected = 'NO'";

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
</center>
<!-- <div class="panel panel-default">
  <div class="panel-body">Batches Awaiting Approval</div>
</div> -->
    <table class="table table-striped " align="center" style="margin-top: 1em;" id="tab_id">

        <thead>
        <tr>
            <th style="text-align:center; padding:0.3em"><small>Envelope No</small></th>
            <th style="text-align:center; padding:0.3em"><small>Batch No</small></th>
            <th style="text-align:center; padding:0.3em"><small>Facility</small></th>
            <th style="text-align:center; padding:0.3em"><small>Date Dispatched from Facility</small></th>
            <th style="text-align:center; padding:0.3em"><small>Date Received at Lab</small></th>
            <th style="text-align:center; padding:0.3em"><small>No. of Samples</small></th>
            <th style="text-align:center; padding:0.3em"><small>Action</small></th>
        </tr>
        </thead>
<?php 

    $SQL = "SELECT  batches.id AS batchID, batch_number, envelope_number, " .
                    "date_dispatched_from_facility, date_rcvd_by_cphl, " .
                    "facility_name, count(batch_id) as nSamples " .

                "FROM batches, dbs_samples " .

                "   WHERE   dbs_samples.in_workSheet = 'NO' " .
                "   AND     batches.id = dbs_samples.batch_id " .
                "   AND     sample_rejected = 'NO' " .

                    "GROUP BY batch_id ".
                    "ORDER BY envelope_number, batch_id ASC";


    $results = DB::select( $SQL );// adjust to show only samples that need approval
    $nBatches = count($results);

?>
    <tbody>
    @for($i=0; $i < $nBatches; $i++ )
        
        <?php $row = (array) $results[$i];   ?>

        <tr class='even'>
            <td class="td_col">{{ $row["envelope_number"] }}</td>
            <td class="td_col"><a href="/approve/{{ $row['batchID'] }}">{{ $row["batch_number"] }}</td>
            <td class="td_col">{{ $row["facility_name"] }}</td>
            <td class="td_col date_dispatched ">{{ $row["date_dispatched_from_facility"] }}</td>
            <td class="td_col date_rcvd">{{ $row["date_rcvd_by_cphl"] }}</td>
            <td class="td_col"><div align='center'> {{ $row["nSamples"] }}</div></td>
            <td class="td_col"><a href="/dbsQ/{{ $row['batchID'] }}" title='Approved samples for this batch'>See Samples</a></td>
        </tr>
    @endfor
    </tbody>
    </table>


    <?php $web_server = env('WEB_HOST', "http://localhost"); ?>
    <link   href="/css/pikaday.css" rel="stylesheet" >
    <link   href="{{$web_server}}/css/select2.min.css" rel="stylesheet" />
    <script src="{{$web_server}}/js/select2.min.js"></script>

    
    <script src="/js/moment.js"></script>

    <!-- JavaScript that is specific to this page -->
    <script type="text/javascript">

        $(function (){

            function format_date( id ){

                var dates = $(id);
                var nDates = dates.length;

                var this_date;
                var formatted_date;


                dates.each(function (idx){

                    this_date = $(this);

                    if( this_date.text().trim() !== "" ) {
                        formatted_date = moment(this_date.text(), "YYYY-MM-DD").format("Do MMM YYYY");
                        this_date.text( formatted_date );
                    }
                });
                
                return;
            }

            
            $("#batch_selector").on("change", function (argument) {
                location.href = "/dbsQ/" + this.value;
            });

            $("#dbs_selector").on("change", function (argument) {
                location.href = "/dbsQ/" + this.value;
            });
        
    
            $("#batch_selector").select2();
            $("#dbs_selector").select2();


            format_date(".date_dispatched");
            format_date(".date_rcvd");

        });

        $(document).ready(function() {
           $('#tab_id').DataTable();
         });

    </script>
@stop