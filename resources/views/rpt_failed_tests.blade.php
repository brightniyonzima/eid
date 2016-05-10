@extends('layouts/layout')

@section('content2')


<?php   use EID\Models\User as User;


        $sql = "SELECT  sum(if(test_1_result is null, 0, 1) + if(test_2_result is null,0,1) +  
                            if(test_3_result is null, 0, 1) + if(test_4_result is null, 0, 1) + 
                                if(test_5_result is null, 0, 1)
                        ) as nTests, 

                        count(test_2_result) + count(test_3_result)   as nRepeats,

                        sum(if(test_1_result =  'fail', 1, 0) + if(test_2_result = 'fail',1,0) +  
                            if(test_3_result = 'fail', 1, 0) + if(test_4_result = 'fail', 1, 0) + 
                                if(test_5_result = 'fail', 1, 0)
                        ) as nFails 
                from dbs_samples";

        $rows = \DB::select( $sql );// returns 1 row

        $nTests = $rows[0]->nTests + 0;
        $nRepeats = $rows[0]->nRepeats + 0;
        $nFails = $rows[0]->nFails + 0;

?>


        <script type="text/javascript">

$(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Fail Rates for EID Tests'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Succeeded on 1st try',
                y: {!! $nTests !!}
            }, {
                name: 'Failed',
                y: {!! $nFails !!}
            }, {
                name: 'Repeats',
                y: {!! $nRepeats !!},
                sliced: true,
                selected: true
            }]
        }]
    });
});
        </script>

    </head>





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
</div><div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>

@stop