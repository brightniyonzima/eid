@extends('layouts/layout')

@section('content2')


<?php   use EID\Models\User as User;

        $sql = "SELECT  users.id as user_id, 
                        concat(users.other_name) as name, 
                        PCR_results_ReleasedBy, 
                        count(*) as nTests 

                FROM dbs_samples, users 

                WHERE PCR_results_ReleasedBy = users.id 
                GROUP BY PCR_results_ReleasedBy 
                ORDER BY nTests DESC limit 5;";

        $rows = \DB::select( $sql );

        $lab_techs = [];
        $tests_done = [];

        foreach ($rows as $r) {
            $lab_techs[] = $r->name;
            $tests_done[] = $r->nTests + 0;         
        }

?>


        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top 5 (EID Lab)'
        },
        xAxis: {
            categories: {!! json_encode($lab_techs) !!} ,  
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' '
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'EID Tests Run',
            data: {!! json_encode($tests_done) !!}
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
</div>
<div id="container" style="min-width: 310px; max-width: 800px; height: 400px; margin: 0 auto"></div>

@stop