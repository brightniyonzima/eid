

<div class="panel panel-default" style="float:left">
  <div class="panel-body">
   	<span style="color: brown"  ><b>{!! EID\Models\Sample::quickStats('batches','pending2approve') !!}</b> Batches pending approval</span> |
    <span style="color: #2F51BA"><b>{!! EID\Models\Sample::quickStats('samples','ready4EIDlab') !!}</b> Samples ready for EID Lab</span> |
    <span style="color: green"  ><b>{!! EID\Models\Sample::quickStats('samples','ready4SClab') !!}</b> Samples ready for Sickle Cell Lab </span>
  </div>
</div>
