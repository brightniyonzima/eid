<?php
$code='code128';
	$o=2;
	$dpi=50;
	$t=50;
	$r=1;
	$rot=0;
	
	$f1='Arial.ttf';
	$f2=6;
	$a1='';
	$a2='B';
	$a3='';
	$text=5656;


	$filename = $system_temp_array2[0];
	// require($class_dir.'/BCGColor.php');
	// require($class_dir.'/BCGBarcode.php');
	// require($class_dir.'/BCGDrawing.php');
	// require($class_dir.'/BCGFont.php');
	if(include($class_dir . '/BCG' . Request::input('code') . '.barcode.php')) {
		if(Request::input('f1') !== '0' && Request::input('f1') !== '-1' && intval(Request::input('f2')) >= 1){
			$font = new BCGFont($class_dir.'/font/'.Request::input('f1'), intval(Request::input('f2')));
		} else {
			$font = 0;
		}
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		$codebar = 'BCG'.Request::input('code');
		$code_generated = new $codebar();
		if(isset(Request::input('a1')) && intval(Request::input('a1')) === 1) {
			$code_generated->setChecksum(true);
		}
		if(isset(Request::input('a2')) && !empty(Request::input('a2'))) {
			$code_generated->setStart(Request::input('a2'));
		}
		if(isset(Request::input('a3')) && !empty(Request::input('a3'))) {
			$code_generated->setLabel(Request::input('a3'));
		}
		$code_generated->setThickness(Request::input('t'));
		$code_generated->setScale(Request::input('r'));
		$code_generated->setBackgroundColor($color_white);
		$code_generated->setForegroundColor($color_black);
		$code_generated->setFont($font);
		$code_generated->parse(Request::input('text'));
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code_generated);
		$drawing->setRotationAngle(Request::input('rot'));
		$drawing->setDPI(Request::input('dpi') == 'null' ? null : (int)Request::input('dpi'));
		$drawing->draw();
		if(intval(Request::input('o')) === 1) {
			header('Content-Type: image/png');
		} elseif(intval(Request::input('o')) === 2) {
			header('Content-Type: image/jpeg');
		} elseif(intval(Request::input('o')) === 3) {
			header('Content-Type: image/gif');
		}

		$drawing->finish(intval(Request::input('o')));
	echo "<img src='$drawing'  />";
	}
?>