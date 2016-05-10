@extends('layouts/layout')

@section('content')

    <style type="text/css">
        .has_data{
            background-color: white;
        }
        .needs_data{
            background-color: yellow;
        }
    </style>
    <?php   function tieBreakValue($this_sample){

                if($this_sample->result1_equals_result2)
                    $default_tieBreak_value = $this_sample->result2;// result1  == result2, so pick any.
                else
                    $default_tieBreak_value = null;// tie break needed: leave it empty, so that user tie-breaks manually
                
                return $default_tieBreak_value;
              }

            function change_tie_break_results($this_sample)
            {
                $change_tie_break = false;
                
                if(Request::has('rn') && Request::get('rn') == 3){
                    $change_tie_break = true;
                }

                if($change_tie_break && !empty($this_sample->tie_break_result)){
                    return true;
                }
                return false;
            }
    ?>
    <?php $scm = new SCManager(); ?>
    <?php $result_number = Request::input('rn') ?: null; ?>
    <?php $scws_id = Request::input('scws') ?: null; ?>
    <?php if($result_number == null || $scws_id == null)
            die("Illegal Route: You can't get here from there!"); ?>
    <?php $sc_samples = $scm->getTestResults($scws_id); ?>
    <?php $possible_results = array('LEFT_BLANK'=>  '',
                                    'NORMAL'    =>  'NORMAL (A/AF)',
                                    'VARIANT'   =>  'VARIANT (AX/AFX)',                                     
                                    'CARRIER'   =>  'CARRIER (AS/AFS)',
                            'SICKLER.TEST_AGAIN'=>  'SICKLER (TEST AGAIN)',
                                    'SICKLER'   =>  'SICKLER (FINAL)',
                                    'INVALID'   =>  'INVALID (TEST AGAIN)',
                                    'FAILED'    =>  'FAILED (INVALID. GIVE UP)');// this array should be declared in SCController
                                    ?>
    
    {!! Form::open(array('route' => 'scstore')) !!}
        
        @include('scd.partials._resultsHeader')    

            {!! Form::hidden('result_number', $result_number) !!}
            {!! Form::hidden('worksheet_number', $scws_id) !!}

            <?php $i = -1; /* tracks tray positions */ ?>

            @foreach($sc_samples as $this_sample)
                <?php

                        if($result_number == 1) $this_result = $this_sample->result1;
                    elseif($result_number == 2) $this_result = $this_sample->result2;
                    elseif($result_number == 3) $this_result = tieBreakValue($this_sample);

                    $td_style = "has_data";
                    if(empty($this_result) || $this_result == "LEFT_BLANK") { $td_style = "needs_data"; }

                    if(change_tie_break_results($this_sample)){
                        $this_result = $this_sample->tie_break_result ?: $this_result;
                    }

                    $i++;
                    $print_control_sample = false;

                ?>

                @if(\SCManager::isControl($i))
                <tr class="{{ $td_style }}">
                    <td>{{ EID\Http\Controllers\SCController::getTrayPosition($i++) }} </td>
                    <td> FASC </td>
                    <td>&nbsp;</td>
                </tr>
                @endif

                <tr class="{{ $td_style }}">
                    <td>{{ EID\Http\Controllers\SCController::getTrayPosition($i) }} </td>
                    <td>{!! $this_sample->infant_id !!} </td>
                    <td>
                        {!! Form::select($this_sample->infant_id, $possible_results, $this_result) !!}
                    </td>
                </tr>

            @endforeach

        @include('scd.partials._resultsFooter')
        
    {!! Form::close() !!}
    
@stop