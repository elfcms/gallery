<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Version of package
    |
    */

    'version' => '0.1',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'basic_package' => '1.1.0',

    /*
    |--------------------------------------------------------------------------
    | Gallery default config
    |--------------------------------------------------------------------------
    |
    */

    'gallery' => [
        'preview' => [
            'auto' => true,
            'width' => 800,
            'height' => 600,
        ],
        'thumbnail' => [
            'auto' => true,
            'width' => 400,
            'height' => 300,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu data
    |--------------------------------------------------------------------------
    |
    | Menu data of this package for admin panel
    |
    */

    "menu" => [
        [
            "title" => "Galleries",
            "lang_title" => "gallery::elf.galleries",
            "route" => "admin.gallery.index",
            "parent_route" => "admin.gallery.index",
            "icon" => "/vendor/elfcms/gallery/admin/images/icons/gallery.png",
            "position" => 120,
            "submenu" => [
            ]
        ],
    ],
];
