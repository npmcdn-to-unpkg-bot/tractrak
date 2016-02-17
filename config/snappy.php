<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => '/usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => [],
    ],
    'image' => [
        'enabled' => true,
        'binary' => '/usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => [],
    ],


];
