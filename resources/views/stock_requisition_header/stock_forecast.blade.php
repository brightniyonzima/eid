@extends('layouts.master')

@section('content')
    <h1>Stock Forecast</h1>
    <hr/>

    <!-- include the calendar js and css files -->
<script src="/js/plugins/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/zabuto_calendar.min.css">




<div id="date-popover" class="popover top"
     style="cursor: pointer; display: block; margin-left: 33%; margin-top: -50px; width: 175px;">
    <div class="arrow"></div>
    <h3 class="popover-title" style="display: none;"></h3>

    <div id="date-popover-content" class="popover-content"></div>
</div>


<!-- define the calendar element -->
<div id="my-calendar"></div>

<!-- initialize the calendar on ready -->
<script type="application/javascript">
    // $(document).ready(function () {
    //     $("#my-calendar").zabuto_calendar();
    // });

var eventData = [
    {"date":"2015-12-07","badge":false,"title":"Example 1"},
    {"date":"2015-12-18","badge":true,"title":"Example 2"},
    {"date":"2016-01-10","badge":true,"title":"Example 2"},
    {"date":"2016-01-15","badge":false,"title":"Example 2"},
    {"date":"2016-01-20","badge":false,"title":"Example 2"},
    {"date":"2016-01-28","badge":true,"title":"Example 2"},
    {"date":"2016-01-24","badge":false,"title":"Example 2"},
    {"date":"2016-01-25","badge":false,"title":"Example 2"}
];

$(document).ready(function () {
                    $("#date-popover").popover({html: true, trigger: "manual"});
                    $("#date-popover").hide();
                    $("#date-popover").click(function (e) {
                        $(this).hide();
                    });

                    $("#my-calendar").zabuto_calendar({
                        data: eventData,
                        action: function () {
                            return myDateFunction(this.id, false);
                        },
                        action_nav: function () {
                            return myNavFunction(this.id);
                        },
                        ajax: {
                            url: "/stock_out",
                            modal: true
                        },
                        legend: [
                            {type: "text", label: "Stock-out within 30 days", badge: "00"},
                            {type: "block", label: "Stock-out within 2 months"}
                        ]
                    });
                });

                function myDateFunction(id, fromModal) {
                    $("#date-popover").hide();
                    if (fromModal) {
                        $("#" + id + "_modal").modal("hide");
                    }
                    var date = $("#" + id).data("date");
                    var hasEvent = $("#" + id).data("hasEvent");
                    if (hasEvent && !fromModal) {
                        return false;
                    }
                    $("#date-popover-content").html('You clicked on date ' + date);
                    $("#date-popover").show();
                    return true;
                }

                function myNavFunction(id) {
                    $("#date-popover").hide();
                    var nav = $("#" + id).data("navigation");
                    var to = $("#" + id).data("to");
                    console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
                }
</script>



@endsection