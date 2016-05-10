@extends('layouts/layout')

@section('content')
   
   <?php 
        // function getTrayPosition($skip_controls = true)
        // {
        //     for($i=0, $j=0; $i < 88; $i++){
        //         if(SCManager::getLocationCode($i, true) === "XXX")
        //             return SCManager::getLocationCode($i, false);
        //         else
        //             return SCManager::getLocationCode($i, true);
        //    }
        // }
   ?>

<!-- 
   @for($i=0, $j=0; $i < 88; $i++)

        @if( SCManager::getLocationCode($i, true) === "XXX")
            <br><b color="red">CRTL = {{ SCManager::getLocationCode($i, false) }}</b><br>
        @else
            {{ ++$j }} = {{ SCManager::getLocationCode($i, true) }} ,
        @endif
   @endfor


 -->
    <section id='s3' class='mm'></section>
    <style type="text/css" media="print">

        @media all {
            .page-break  { display: none; }
        }

        @media print {
            .start_new_page  { display: block; page-break-before: always; }
        }
    </style>

    <?php

        $j = 10;
        $pos = 0;
        $wsid = 0;
        $is_control = true;
        $scm = new SCManager;


        if(!empty($ws))// NB: $ws is set by controller
            $wsid = $ws;
        else if(Request::has('id'))
            $wsid = Request::get('id');
        else
            $wsid = $scm->createWorksheet();


        $data = $scm->getWorksheetData($wsid);
        $controls = $scm->getControlSamples();

        $nSamples = count($data);
        $nControls = count($controls);

    ?>
    <center>
        <div style="font-size: 1.25em; color: #aaa">Sickle Cell Worksheet #: {{ $wsid }} </div>        
    </center>


    @for($i=0; $i<$nSamples; $i++, $j++, $pos++)

        <?php 

            $sample = $data[ $i ]; 
            $location = SCManager::getLocationCode($i, false);
            $is_control = ($j==10) ? true : false ;
        ?>

        @if($is_control)
            <!-- Display the control sample -->
            <?php $j=0; $is_control=false; ?>
            <div style="float: left; margin:1.85em 0.5em 0.5em 0.5em; border: 1px solid #eee">
                <center style="font-size: 0.5em; color: red">
                    
                    <b>
                    {{ SCManager::getLocationCode($pos++, false) }}</b>
                    <div style="font-size: 1em;">&nbsp;CONTROL</div>
                    <b>XXX</b>
                </center>
            </div>

        @endif


        <div style="float: left; margin:1em;">
            <center style="font-size: 0.5em;">
                <b>
                {{ SCManager::getLocationCode($pos, false) }}</b>
                {!! \DNS1D::getBarcodeHTML($sample->id, "C128A", 1, 44) !!}
                {{ $i+1 }} = {{ $sample->id}}
            </center>
        </div>

        


    @endfor

    <p style="clear:both; margin-left: 24em;"><a href="/scwsList">List of Worksheets</a></p>

@stop