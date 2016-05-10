@extends('layouts/layout')

@section('content')

<h1>Find Rejected Samples</h1>
<style type="text/css">
    .tag{
        clear: left;
        float: left
        width: 150px;
    }
</style>
<form action="/rejected_results">    

    <label for="q" class="tag">From: </label>
    <input type="date" id="from" name="from" />

    <label for="to" class="tag">To: </label>
    <input type="date" id="to" name="to" />
    <?php $w_id = 1; ?>

<!-- <button type="submit" id="x">SEARCH</button> -->
    <div class="btn-group">

        <button type="button" 
                class="btn btn-primary btn-xs dropdown-toggle"
                style="font-size: 1.1em; width:7em;height: 2.5em; float:right; margin-bottom: 0.3em; " 
                data-toggle="dropdown" aria-expanded="false">Action&nbsp;&nbsp;&nbsp;<span class="caret"></span>                      
        </button>

        <ul class="dropdown-menu pull-left worksheet_actions"  role="menu">

            <li><a id="results"   worksheet="{{ $w_id }}"    target="_blank" href="/rejected_results?">
                    <span class="glyphicon glyphicon-th pull-left">&nbsp;</span>Print Results
                </a>
            </li>
            <li><a id="envelopes" target="_blank"  worksheet="{{ $w_id }}"  target="_blank"  href="/hc_env/0?">
                    <span class="glyphicon glyphicon-save-file pull-left">&nbsp;</span>Print Envelopes</a>
            </li>
        </ul>
    <div class="btn-group">

</form>

<script type="text/javascript">
    
    $("#from").on("change", function (argument) {
        var link;
        var href;

        link = $("#results");
        href = link.attr("href");
        href = href + "&from="+this.value;
        link.attr("href", href);

        link = $("#envelopes");
        href = link.attr("href");
        href = href + "&from="+this.value;
        link.attr("href", href);
    });

    $("#to").on("change", function (argument) {
        var link;
        var href;

        link = $("#results");
        href = link.attr("href");
        href = href + "&to="+this.value;
        link.attr("href", href);

        link = $("#envelopes");
        href = link.attr("href");
        href = href + "&to="+this.value;
        link.attr("href", href);
    });


    function setSearchDate(date_type, date_value) {
        // use this to DRY the #from and #to jQuery above
        // Also, current implementation adds the most recently selected date to the URL params as &from= and &to=
        //  (This works correct, but is obviously a hack: &from and &to should only appear once in the URL )
    }


    $("#x").on("click", function () {
        alert( $("#from").val() );
    });

</script>

@if( Request::has('q') )
    <?php   $sample = Request::get('q'); 

            $sql1 = "SELECT dbs_samples.id as sample_id, 
                            'EID' as test_type,
                            infant_name, infant_exp_id, 
                            worksheet_number,  sample_rejected,
                            batch_id, batch_number, 
                            PCR_test_requested, SCD_test_requested , 
                            accepted_result as PCR_test_result, 
                            SCD_test_result
                             

                    FROM    (dbs_samples left join batches on dbs_samples.batch_id = batches.id )
                            left join worksheet_index on dbs_samples.id = worksheet_index.sample_id 

                    where     dbs_samples.id = '$sample'";
                     

            $sql2 = "SELECT  'x' as sample_id, 
                            'SCD' as test_type,
                            infant_name, infant_exp_id, 
                            worksheet_number,  sample_rejected,
                            batch_id, batch_number, 
                            PCR_test_requested, SCD_test_requested , 
                            accepted_result as PCR_test_result, 
                            SCD_test_result
                             

                    FROM    (dbs_samples left join batches on dbs_samples.batch_id = batches.id )
                            left join sc_worksheet_index on dbs_samples.id = sc_worksheet_index.sample_id 

                    where     dbs_samples.id = '$sample'";
                     


            $sql = $sql1 . " UNION " . $sql2;

            $rows = DB::select($sql);
            $nResults = count($rows);


            function show_approval_status($this_sample)
            {
                if($this_sample->sample_rejected == 'NOT_YET_CHECKED')
                    return 'NOT_YET_CHECKED';

                if($this_sample->sample_rejected == 'YES')
                    return 'REJECTED';
                else
                    return 'ACCEPTED';
            }

            function show_worksheet_number($this_sample)
            {
                $worksheet_type = $this_sample->test_type;

                if($this_sample->worksheet_number == null) 
                    return "NOT in <b>$worksheet_type</b> Worksheet";
                
                if($worksheet_type == "EID")
                    return '<a href="/ws?i=view&sr=1&dbs=' . $this_sample->sample_id . 
                            '&ws=' . $this_sample->worksheet_number . '">' . 
                            $this_sample->worksheet_number . ' [' . $worksheet_type . '] ' . '</a>';

                if($worksheet_type == "SCD")
                    return '<a target="_blank" href="/scws/' . $this_sample->worksheet_number  . '?pp=1">' . 
                            $this_sample->worksheet_number . ' [' . $worksheet_type . '] ' . '</a>';


            }

            function show_EID_test($this_sample)
            {
                if($this_sample->PCR_test_requested == "YES")
                    return "YES";
                else
                    return "<b style='color:red; background-color:yellow'>NO</b>";
            }

            function show_batch_number($this_sample)
            {
                return '<a href="samples/' . $this_sample->batch_id . '">' . $this_sample->batch_number . '</a>';
            }
    ?>

    <table border="1">
    <style type="text/css">
        td, th {
            padding: 5px;
            text-align: center;
        }

    </style>

    @if($nResults == 0)
        <h5 style="color:red">That sample ({{ $sample }}) was not found</h5>
    @else
        <h5 style="color:blue">Results for sample {{ $sample }} </h5>
        <tr>
            <th>Infant Name</th>
            <th>EXP ID</th>
            <th>Sample Approved?</th>
            <th>Worksheet</th>

            <th>Batch Number</th>
            <th>Do EID test?</th>
            <th>Do SCD test?</th>            
            <th>EID Result</th>
            <th>SC Result</th>
        </tr>

    @endif

    @foreach($rows as $this_row)
        <tr>
            <td>{{  $this_row->infant_name}}</td>
            <td>{{  $this_row->infant_exp_id}}</td>
            <td>{{  show_approval_status($this_row) }}</td>
            <td>{!! show_worksheet_number($this_row) !!}</td>
            <td>{!! show_batch_number($this_row) !!}</td>
            <td>{!! show_EID_test($this_row) !!}</td>
            <td>{{  $this_row->SCD_test_requested }}</td>
            <td>{!! $this_row->PCR_test_result ?: "NO RESULTS" !!}</td>            
            <td>{!! $this_row->SCD_test_result ?: "NO RESULTS" !!}</td>

        </tr>
    @endforeach
    </table>

@endif

@stop