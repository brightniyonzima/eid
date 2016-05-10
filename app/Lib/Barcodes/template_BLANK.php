<?php

class tpl_BLANK {
	function __header ( $width, $title ) {
		echo '<table width="'.$width.'" border="0" cellpadding="0" cellspacing="0" align="center">';
	}

	function __footer ( $width, $title ) {
		echo '</table>';
	}

	function __row_start ( $attributes ) {
		echo '<tr>';
	}

	function __row_stop ( $attributes ) {
		echo '</tr>';
	}

	function __cell_start ( $text, $attributes ) {
		$code = '';
		reset( $attributes );
		while( list( $key, $value ) = each( $attributes ) )
			$code .= ' '.$key.'="'.$value.'"';
		echo '<td'.$code.'>'.$text;
	}

	function __cell_stop ( $text, $attributes ) {
		echo '</td>';
	}
}