<?php

phpinfo();

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

echo "1";

		if($_REQUEST['f1'] !== '0' && $_REQUEST['f1'] !== '-1' && intval($_REQUEST['f2']) >= 1){
echo "1a-a";

			$font = new BCGFont('/font/'.$_REQUEST['f1'], intval($_REQUEST['f2']));
		} else {
echo "1a-b<br>";

			$font = 0;
		}

echo "1b<br>";

		$color_black = new BCGColor(0, 0, 0);
echo "1c";

		$color_white = new BCGColor(255, 255, 255);
		$codebar = 'BCG'.$_REQUEST['code'];
		$code_generated = new $codebar();
echo "2";

		if(isset($_REQUEST['a1']) && intval($_REQUEST['a1']) === 1) {
			$code_generated->setChecksum(true);
		}
		if(isset($_REQUEST['a2']) && !empty($_REQUEST['a2'])) {
			$code_generated->setStart($_REQUEST['a2']);
		}
		if(isset($_REQUEST['a3']) && !empty($_REQUEST['a3'])) {
			$code_generated->setLabel($_REQUEST['a3']);
		}
		$code_generated->setThickness($_REQUEST['t']);
		$code_generated->setScale($_REQUEST['r']);
		$code_generated->setBackgroundColor($color_white);
		$code_generated->setForegroundColor($color_black);
		$code_generated->setFont($font);
		$code_generated->parse($_REQUEST['text']);
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code_generated);
		$drawing->setRotationAngle($_REQUEST['rot']);
		$drawing->setDPI($_REQUEST['dpi'] == 'null' ? null : (int)$_REQUEST['dpi']);
		$drawing->draw();
		if(intval($_REQUEST['o']) === 1) {
			header('Content-Type: image/png');
		} elseif(intval($_REQUEST['o']) === 2) {
			header('Content-Type: image/jpeg');
		} elseif(intval($_REQUEST['o']) === 3) {
			header('Content-Type: image/gif');
		}

		$drawing->finish(intval($_REQUEST['o']));
	echo "<img src='$drawing'  />";

?>