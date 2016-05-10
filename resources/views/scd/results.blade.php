@extends('../../layouts/layout')

@section('content')
    <h4 style="color: #aaa; margin-top: -75px; text-align: center">Sickle Cell Results : Worksheet # {{ $ws }}</h4>
    <style type="text/css">
        .results_container_1{ width: 300px; float:left; margin-left: 100px; font-size: 15px; }
        .results_container_2{ width: 300px; float:left; margin-left: 100px; font-size: 15px;}
        .page_number{ width: 300px; clear: both; background-color: #eee; text-align: center; font-weight: bold}
        .header{ background-color: blue; padding: 5px; color: white; font-weight: bold; }

        .pos{ width: 75px; float: left; clear: left;}
        .sample{ width: 100px; float: left; }
        .result{ width: 125px; float: left; }

        .control { background-color: #eee; border-bottom: 1px solid #bbb;}
        .sc_highlighted_result { background: yellow;}
        td{ padding : 2px; }
    </style>

    <div style="background: white; width: 850px; margin-top: 50px;">&nbsp;</div>
<?php

    function print_header($header_position)
    {
        $open_tags = "<div class='results_container_$header_position'>" .
                        "<div class='page_number'>Page $header_position</div>" .
                        "<div class='pos header'>Position</div>" .
                        "<div class='sample header'>Sample ID</div>" .
                        "<div class='result header'>Result</div>";
        $close_tag = "</div>";

        if($header_position == 1){
            echo $open_tags;
        }else if($header_position == 2){
            echo $close_tag;
            echo $open_tags;
        }else if($header_position == 3){
            echo $close_tag;
        }
    }

    function print_close_tag()
    {
        print_header(3);
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

    function sc_print($this_result, $result_to_highlight='')
    {
        $fmt = empty($this_result->tie_break_result) ? "control" : "";
        $extra_css = '';

        if($this_result->sample_id == $result_to_highlight){
            $extra_css = 'sc_highlighted_result';
        }


        $output =   "<div class='results_row'>" .
                        "<div class='pos $fmt $extra_css'>&nbsp;" . $this_result->position . "</div>" .
                        "<div class='sample $fmt $extra_css'>&nbsp;" . $this_result->sample_id . "</div>" .
                        "<div class='result $fmt $extra_css'>&nbsp;" . $this_result->tie_break_result . "</div>" .
                    "</div>";
        echo $output;
    }

    $i=0;
    $matrix = \SCManager::getTrayLayout();
    $results = getSickleCellResults($ws);
    $highlighted = Request::get('s', '');

    foreach ($matrix as $key => $tray_position) {

        if($i == 0) print_header(1);
        if($i == 44) print_header(2);
        
        $is_control = empty($results[$tray_position]);
        $is_control ? print_control($tray_position) : sc_print($results[$tray_position], $highlighted);

        $i++;
        if($i == 88) print_close_tag();
    }

?>
    

    <?php if(Request::has('pp')) 
            $print_me = "window.onload = function() { window.print() }";
          else
            $print_me = "";
    ?>  

    <script type="text/javascript">
          {{ $print_me }}
    </script>
@stop