@extends('layouts/layout')

@section('content2')


<?php   use EID\Models\User as User;

        $sql = "SELECT  users.id as user_id, 
                        concat(users.other_name) as name, 
                        count(dbs_samples.id) as nSamples   
                
                FROM batches, dbs_samples, users 
                
                WHERE   batches.id = dbs_samples.batch_id AND users.id = batches.entered_by 

                GROUP BY user_id 
                ORDER BY nSamples 
                DESC LIMIT 5;";

        $rows = \DB::select( $sql );

        $data_entrants = [];
        $samples_entered = [];

        foreach ($rows as $r) {
            $data_entrants[] = $r->name;
            $samples_entered[] = $r->nSamples + 0;         
        }

?>


        <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top 5 Data Entrants'
        },
        xAxis: {
            categories: {!! json_encode($data_entrants) !!} ,  
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ' ',
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
            name: 'Samples Entered',
            data: {!! json_encode($samples_entered) !!}
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