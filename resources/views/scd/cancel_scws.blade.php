@extends('../../layouts/layout')

@section('content')


        <center>
        <div style="width:80%">
            <h2 style="color:gray">Sickle Cell Worksheet # {{ $scws }}</h2>
        </div>
        <div style="width:80%; ">
            <div style="float:left;">
                <img src="/images/warning_icon.png" style="height:250px; width:auto "
                        title="warning! If you delete, there's no un-do!">
            </div>
            <div style="float:left;margin-left:75px;">
                <h3 style="color:gray">Delete this Worksheet?</h3>
                
                <a href="/wlist#" class="btn btn-success" style="font-size: 2em; width:5em;">
                    No
                </a>
                <p>&nbsp;</p>
                <p>&nbsp;</p>

                <span style="color:darkblue; font-size: larger">Warning: if you say yes, there's no way to un-do!</span><br> 
                <span style="color:gray">(But choosing "Yes" allows you to put these samples on another worksheet)</span><br> 

                <a href="#" class="btn btn-danger"  style="font-size: 1.5em; width:10em;" id="delete_scws">
                    YES - Delete
                </a>

            </div>
            
        </div>
        </center>
    <script type="text/javascript">
        $("#delete_scws").on('click', function () {
            var delete_worksheet = confirm('xxx?');

            if(delete_worksheet){
                location.href = "/delete_scws/{{ $scws }}";
                return true;
            }else{
                return false;
            }
            
        });
    </script>
@stop