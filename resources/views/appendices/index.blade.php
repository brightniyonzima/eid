
@extends('appendices.container')

@section('apendix_content')
<div id="d3" class="panel panel-default">
	<div class="panel-heading"><b>Appendices</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>$post_url)) !!}
		<table class='table table-striped table-condensed' id='appendix_tab'>
			<tr>
				<th width='2%'>#</th>
				<th>Appendix</th>
				<th>Code</th>
				<th>Action</th>
			</tr>
			<?php $x=1 ?>
			
			@foreach ($appendices AS $apdx)				
				<?php 
				if($apdx->inactive==1){
					$status=0;
					$d_label='Activate';
					$d_clr="style='color:red'";
				}else{
					$status=1;
					$d_label='Deactivate';
					$d_clr="";
				}

				echo "<tr $d_clr><td>".$x++."</td>";

				$item_edit=false;
				if(isset($edit_id)) $item_edit=$edit_id==$apdx->id?true:false; 
				if($item_edit==true){
					echo "<td>".Form::text('appendix',$apdx->appendix)."</td>";
					echo "<td>".Form::text('code',$apdx->code)."</td>";
					echo "<td>".Form::submit('Save',array('class'=>'btn btn-primary'))."</td>";
				}else{
					echo "<td>".$apdx->appendix."</td>";
					echo "<td>".$apdx->code."</td>";
					echo "<td>".link_to("appendices/edit/$cat_id/".$apdx->id,'Edit');
					echo " | ".link_to("appendices/deactivate/$cat_id/".$apdx->id."/$status",$d_label)."</td>";

				}
				?>
			</tr>
			@endforeach
		</table>

		<?php if(!isset($edit_id)) echo "<label class='add_item' onClick='addItem()'>Add</label>" ?><br>
		<div id='save_btn_hide'>{!! Form::submit('Save',array('class'=>'btn btn-primary')) !!}</div>
	  {!! Form::close() !!}
	</div>
</div>	

<script type="text/javascript">
function addItem(){
	document.getElementById('save_btn_hide').style.display='inline-block';
	var tab=document.getElementById('appendix_tab');
	var rowCount = tab.rows.length;
	var row = tab.insertRow(rowCount);
	
	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);

	var cnt=rowCount;
	cell0.innerHTML=cnt+"<input type='hidden' name='nrs["+cnt+"]' value="+cnt+">";
	cell1.innerHTML="<input class='input_md' type='text' name='appendix["+cnt+"]' value=''>";
	cell2.innerHTML="<input class='input_md' type='text' name='apdx_code["+cnt+"]' value=''>";
	
	cell3.setAttribute("class","rm_item");
    cell3.setAttribute("onClick","removeItem(this)");
    cell3.innerHTML="Remove";
    //count.value=countv;
}
</script>
@endsection