<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF'),
        'timeout' => false,
        'options' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => env('WKHTMLTOIMG'),
        'timeout' => false,
        'options' => array(),
    ),


);