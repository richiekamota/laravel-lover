<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => env('PDF_BINARY', 'C:/sites/_tools/wkhtmltopdf/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => 'C:/sites/_tools/wkhtmltopdf/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
