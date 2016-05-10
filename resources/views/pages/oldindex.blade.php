@extends('layouts/layout')

@section('content')
<div class="starter-template">
    <h1>
        {{ Auth::check() ? "Welcome, " . Auth::user()->username : "Welcome to the EID database" }}
    </h1>






<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>
<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>
<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>
<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>
<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>
<div style="float: left; margin:2em;">
	<?php echo \DNS1D::getBarcodeHTML("123456789", "C128A", 1, 55);?>	
</div>

<p>&nbsp;</p>
<?php


	echo env('DB_DATABASE', 'cyrax');

	// $filename = '/tmp2/xyz.txt';
	// $somecontent = "Add this to the file\n";

	// // Let's make sure the file exists and is writable first.
	// if (is_writable($filename)) {

	//     // In our example we're opening $filename in append mode.
	//     // The file pointer is at the bottom of the file hence
	//     // that's where $somecontent will go when we fwrite() it.
	//     if (!$handle = fopen($filename, 'a')) {
	//          echo "Cannot open file ($filename)";
	//          exit;
	//     }

	//     // Write $somecontent to our opened file.
	//     if (fwrite($handle, $somecontent) === FALSE) {
	//         echo "Cannot write to file ($filename)";
	//         exit;
	//     }

	//     echo "Success, wrote ($somecontent) to file ($filename)";

	//     fclose($handle);

	// } else {
	//     echo "The file $filename is not writable";
	// }




// /**
//  * Path to the 'app' folder
//  */
// echo app_path() . "<br>";
// /**
//  * Path to the project's root folder
//  */
// echo base_path() . "<br>";
// /**
//  * Path to the 'public' folder
//  */
// echo public_path() . "<br>";
// /**
//  * Path to the 'app/storage' folder
//  */
// echo storage_path() . "<br>";
?>

</div>


</div>

	
  	<script type="text/javascript">
  		$(function () {

			$('.buttonset-rd').bsFormButtonset('attach');

			$('.selectpicker').selectpicker({style: 'btn-default'});

			// $("#cx").change(function(){
			// 	$("#s").show();
			// });

			$("#cx2").change(function(){

				$('button[data-id=cx2]').find('span.filter-option.pull-left').text( "Worksheet #: " + this.value );
			});


  		});


  	</script>


@stop

<?php 

	// $p = PDF::loadView("lab.worksheet_details")->setPaper('a4')->setOrientation('landscape')->setWarnings(false);
	// $p->save('myfile79.pdf');


// $pdf = PDF::loadView('lab.worksheet_details');
// $pdf->download('invoice.pdf');

// PDF::loadFile('http://www.github.com')->stream('github.pdf');

// 	function x(){
		
// 		// unlink('/var/www/docRoot/eid/public/myfile79b.pdf');
// 		// $p = PDF::loadView("lab.worksheet_details")->setPaper('a4')->setOrientation('landscape')->setWarnings(false);
// 		// $p->save('/var/www/docRoot/eid/public/myfile79b.pdf', true);
// 	 //   	return Response::download('/var/www/docRoot/eid/public/myfile79b.pdf', 'test.pdf', 
// 	 //   		['content-type' => 'application/pdf', 'content-disposition' => 'attachment']);

// 	   	// return response()->download('/var/www/docRoot/eid/public/myfile79b.pdf')->deleteFileAfterSend(true);

// $path = '/var/www/docRoot/eid/public/myfile79b.pdf';
// $response = Response::make(file_get_contents($path));
// $response->header('Content-Type', 'application/octet-stream');
// $response->header('Content-Transfer-Encoding', 'binary');
// $response->header('Content-Disposition', 'attachment; filename='.$path);
// $response->header('Content-Length', filesize($path));


// 	}

// 	x();
?>
