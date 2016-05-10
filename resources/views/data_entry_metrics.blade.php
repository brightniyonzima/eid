@extends('layouts/layout')

@section('content2')

    <div class="container">
        <h1>Team Performance Metrics</h1>

    <form action="/data_entry_performance">
        <table>
            <tr>
                <td>Select Team:</td>
                <td style="padding: 10px">
                    <select class="form-control" name="team">
                        <option value="DATA_ENTRY">Data Entry Team</option>
                        <option value="SAMPLE_VERIFICATION">Sample Verification Team</option>
                        <option value="EID_LAB">EID Lab</option>
                        <option value="SCD_LAB">Sickle Cell Lab</option>
                    </select>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>Select Period:</td>
                <td style="padding: 10px">
                    <select class="form-control" name="period">
                        <option value="1">Today</option>
                        <option value="7">Past week (7 days)</option>
                        <option value="30">Past Month (30 days)</option>
                        <!-- <option value="xx">Custom...</option> -->
                    </select>        
                </td>
                <td>
                    <input type="submit" class="btn btn-primary" name="submit" value="Download Data" />
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
        </table>
    </form>
    </div>

@stop