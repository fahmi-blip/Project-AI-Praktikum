<?php

return [
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [],
    'options' => [
        'public_path' => base_path('public'),
        'font_cache' => storage_path('fonts'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => base_path(),
        'allowed_protocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],
        'artifactPathValidation' => null,
        'log_output_file' => null,
        'font_height_ratio' => 1.1,
        'enable_php' => false,
        'enable_remote' => true,
        'enable_css_float' => false,
        'enable_html5_parser' => true,
    ],
];