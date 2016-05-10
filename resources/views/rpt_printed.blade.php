@extends('layouts/layout')

@section('content2')


<?php   use EID\Models\User as User;


        $sql = "SELECT 'SCD' as test_type, count(id) as nResults, count(printed_SCD_results ) as nPrinted
                    FROM batches 
                    WHERE tests_requested in ('SCD', 'BOTH_PCR_AND_SCD') 

                UNION 

                SELECT 'EID' as test_type, count(id) as nResults, count(printed_PCR_results ) as nPrinted
                    FROM batches 
                WHERE tests_requested in ('PCR', 'BOTH_PCR_AND_SCD');";

        $rows = \DB::select( $sql );// returns 1 row

        $sc_total = $rows[0]->nResults + 0;
        $sc_printed = $rows[0]->nPrinted + 0;

        $eid_total = $rows[1]->nResults + 0;
        $eid_printed = $rows[1]->nPrinted + 0;

?>


        <script type="text/javascript">

$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total Results Ready vs Results Printed'
        },
        xAxis: {
            categories: [
                'EID',
                'Sickle Cell'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Results Available'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Ready',
            data: [{{ $eid_total }} , {{ $sc_total }} ]
        }, {
            name: 'Printed',
            data: [{{ $eid_printed }} , {{ $sc_printed }}]
        }]
    });
});

        </script>

    </head>




        <script type="text/javascript">

        </script>


<script src="/js/highcharts.js"></script>
<script src="/js/exporting.js"></script>        


<div  class="container" style="min-width: 310px; max-width: 800px;">
<div class="btn-group" style="float: right">

    <button type="button" 
            class="btn btn-default btn-xs dropdown-toggle"
            style="font-size: 1.1em; float:right" 
            data-toggle="dropdown" aria-expanded="false">
        Menu <span class="caret"></span>                      
    </button>


    <ul class="dropdown-menu pull-right worksheet_actions"  role="menu">
        <li><a href="/qty">Top 5 (Data Entry)</a></li>
        <li><a href="/qty_eid">Top 5 (EID Lab)</a></li>
        <li class="divider"></li>
        <li><a href="/fail_rate">EID Lab Fail Rates</a></li>
        <li><a href="/rpt_printed">Results Ready vs Results Printed</a></li>
        <li class="divider"></li>
        <li><a href="/ws_upload_time.html">Worksheet Upload Times</a></li>
    </ul>
</div>
</div>
<div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>

@stop