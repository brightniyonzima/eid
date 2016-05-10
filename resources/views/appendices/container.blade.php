@extends('Admin.app')

@section('admin-content')
<?php $cats=EID\Models\AppendixCategory::select('id','category')->orderby('id')->get() ?>
	
	<div class="row">
		<div class="col-sm-3 ">		 		
			<!-- <ul class="list-group nav nav-pills nav-stacked"> -->
				<?php
				if(empty($cat_id)){
					$cat_id=1;
				} 
				
				foreach ($cats as $cat) {
					$actv=($cat->id==$cat_id)?'active':'';
					echo "".link_to('appendices/index/'.$cat->id,$cat->category,["class"=>"list-group-item $actv"])."";
				}
				?>			
			<!-- </ul> -->
		</div>
		<div class="col-sm-9">
			
			@yield('apendix_content')
		</div>
	</div>

@endsection
