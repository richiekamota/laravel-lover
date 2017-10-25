<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => env('PDF_BINARY', '/usr/local/wkhtmltopdf'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/local/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
