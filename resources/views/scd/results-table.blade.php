@extends('../../layouts/layout')

@section('content')
    <h3 style="color: #aaa; margin-top: 0px; text-align: center">Sickle Cell Results : Worksheet # {{ $ws }}</h3>
    <style type="text/css">
        #results_wrapper{ width: 500px; margin:auto; }
        td{ padding : 2px; }
    </style>
    <table style="border: 1px solid gray; " id="results">
        <thead>
            <tr>
                <th>Position</th>
                <th>Sample ID</th>
                <th>Result</th>
            </tr>
        </thead>

<?php
    $matrix = \SCManager::getTrayLayout();
    $results = getSickleCellResults($ws);

    foreach ($matrix as $key => $tray_position) {
        $is_control = empty($results[$tray_position]);
        $is_control ? print_control($tray_position) : sc_print($results[$tray_position]);
    }


function getSickleCellResults($scws)
{
    $sql = "select * from sc_worksheet_index where worksheet_number = '$scws' order by position asc";
    $db_rows = \DB::select($sql);
    $results = [];

    // convert database results into an associative array
    foreach ($db_rows as $this_row) {
        $i = $this_row->position;
        $results[$i] = $this_row;
    }

    return $results;
}

function print_control($tray_position)
{
    $ctrl = new StdClass;
    $ctrl->sample_id = "FASC";
    $ctrl->position = $tray_position;
    $ctrl->tie_break_result = "";

    sc_print($ctrl);
}

function sc_print($this_result)
{
    $output =   "<tr>" .
                    "<td>" . $this_result->position . "</td>" . 
                    "<td>" . $this_result->sample_id . "</td>" . 
                    "<td>" . $this_result->tie_break_result . "</td>" .
                "</tr>";

    echo $output;
}

?>
    </table>
    <script type="text/javascript">
        $("#results").DataTable({});
    </script>
@stop