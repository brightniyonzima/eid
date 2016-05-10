@extends('layouts/layout')

@section('content')

<h1>Search for Sample</h1>

<form>
    Sample ID: 
    <input type="text" id="q" name="q" />
    <button type="submit">SEARCH</button>
</form>

@if( Request::has('q') )
    <?php   $sample = Request::get('q'); 

            $sql1 = "SELECT dbs_samples.id as sample_id, 
                            'EID' as test_type,
                            infant_name, infant_exp_id, 
                            worksheet_number,  sample_rejected,
                            batch_id, batch_number, 
                            PCR_test_requested, SCD_test_requested , 
                            accepted_result as PCR_test_result, 
                            SCD_test_result,
                            test_1_result,
                            test_2_result,
                            test_3_result,
                            test_4_result,
                            test_5_result,
                            '' as tie_break_result


                    FROM    (dbs_samples left join batches on dbs_samples.batch_id = batches.id )
                            left join worksheet_index on dbs_samples.id = worksheet_index.sample_id 

                    where     dbs_samples.id = '$sample'";
                     

            $sql2 = "SELECT dbs_samples.id as sample_id, 
                            'SCD' as test_type,
                            infant_name, infant_exp_id, 
                            worksheet_number,  sample_rejected,
                            batch_id, batch_number, 
                            PCR_test_requested, SCD_test_requested , 
                            accepted_result as PCR_test_result, 
                            SCD_test_result,
                            test_1_result,
                            test_2_result,
                            test_3_result,
                            test_4_result,
                            test_5_result,
                            tie_break_result


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

            function show_EID_results($this_sample)
            {
                return show_results($this_sample, "EID");
            }
            function show_SCD_results($this_sample)
            {
                return show_results($this_sample, "SCD");    
            }
            function show_results($this_sample, $required_test_type)
            {
                static $previous_test_type = "NONE";
                static $nWorksheets = 0;

                if($this_sample->test_type != $required_test_type){
                    return "&nbsp;";
                }

                if( $previous_test_type != $this_sample->test_type){// got new type of test
                    $previous_test_type = $this_sample->test_type;// reset
                    $nWorksheets = 1;
                }else{
                    $nWorksheets++;
                }

                // print the result:
                if($this_sample->test_type == "EID"){
                    return $this_sample->{"test_" . $nWorksheets . "_result"};
                }else{
                    $scws_result = $this_sample->tie_break_result ?: "NOT YET READY";
                    return $scws_result;
                }
            }

            function show_worksheet_number($this_sample)
            {

                static $previous_test_type = "NONE";
                static $nWorksheets = 0;

                if( $previous_test_type != $this_sample->test_type){// got new type of test
                    $previous_test_type = $this_sample->test_type;// reset
                    $nWorksheets = 1;
                }else{
                    $nWorksheets++;
                }


                $worksheet_type = $this_sample->test_type;

                if($this_sample->worksheet_number == null) 
                    return "NOT in <b>$worksheet_type</b> Worksheet";
                

                if($worksheet_type == "EID"){
                    return '<a href="/ws?i=view&sr=1&dbs=' . $this_sample->sample_id . 
                                '&ws=' . $this_sample->worksheet_number . '&t='. $nWorksheets .'">' . 
                                $this_sample->worksheet_number . ' [' . $worksheet_type . '] ' . 
                            '</a> ';
                }

                if($worksheet_type == "SCD"){
                    $title = $this_sample->worksheet_number;
                    $attributes = array('target' => '_blank');

                    $link1 = link_to('/scws/' . $this_sample->worksheet_number . '?pp=1', $title, $attributes);
                    $link2 = link_to('/scd_results/' . $this_sample->worksheet_number . 
                                            '?pp=1&s=' . $this_sample->sample_id, 'Results', $attributes);

                    return $link1 . " | " . $link2;
                }


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
            <td>{!! show_EID_results($this_row)  !!}</td>            
            <td>{!! show_SCD_results($this_row)  !!}</td>

        </tr>
    @endforeach
    </table>

@endif

@stop