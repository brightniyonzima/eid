  
    <?php $scm = new SCManager(); ?>
    <?php $worksheets = $scm->getWorksheets(); ?>
    <style type="text/css">
        td { padding: 5px; text-align: center; }
        th { padding: 10px }
    </style>
    <style type="text/css">
        .cancel_scws:hover{
            background-color: red;
            color: white;
        }
        #scwsTable tr:hover{
            background-color: #ffff99;
        }
    </style>

    {!! Form::open(array('route' => 'scstore')) !!}
<section id='s3' class='mm'></section>

        <table border="1" id="scwsTable">
        @include('scd.partials._wsListHeader')    


            @foreach($worksheets as $this_worksheet)

                <tr>
                    <td>{!! $this_worksheet->id !!} </td>
                    <td>{!! $this_worksheet->DateCreated !!} </td>
                    <td>{!! $scm->showResultsStatus(1, $this_worksheet) !!} </td>
                    <td>{!! $scm->showResultsStatus(2, $this_worksheet) !!} </td>
                    <td>{!! $scm->showResultsStatus(3, $this_worksheet) !!} </td>
                    <td><a target="_blank" href="/scws/{{ $this_worksheet->id }}?pp=1">PRINT</a></td>
                    <td> 
                        <a href="/cancel_scws/{{ $this_worksheet->id }}" class="btn btn-default cancel_scws">
                            <span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;Cancel Worksheet
                        </a>
                    </td>

                </tr>

            @endforeach

        </table>

            <center style="margin-top: 1em;">

                <a href="/samples?sc=1">Sample Reception</a> |
                <a href="/batchQ">Approval of Samples</a> |
                <a href="/scws_maker">Create worksheet</a>
                
            </center>


    {!! Form::close() !!}

<script type="text/javascript">
$(document).ready(function() {
    // $('#scwsTable').DataTable();
} );
</script>


