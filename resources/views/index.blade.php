@extends('user_roles.container')
@section('user_role_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>User roles</b></div>
	<div class="panel-body">
		
		
		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Role</th>
				<!-- <th>Permissions</th> -->
				<!-- <th width='8%' /> -->
				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($user_roles AS $user_role)		 
			<tr>
			<?php 
			echo "<td>$user_role->description</td>";
			//echo "<td>$user_role->permissions</td>";
			// echo "<td></td>";
			
			/*echo "<td>".link_to("user_roles/show/$user_role->id","View")."";
			echo " | ".link_to("user_roles/edit/$user_role->id","Edit")."</td>";*/
		
			?>
			</tr>		 
			@endforeach
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  	$('#tab_id').DataTable();
  });

</script>
@endsection

