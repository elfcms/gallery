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

    'version' => '1.3.0',

    /*
    |--------------------------------------------------------------------------
    | Version of ELF CMS Basic
    |--------------------------------------------------------------------------
    |
    | Minimum version of ELF CMS Basic package
    |
    */

    'elfcms_package' => '2.0.5',

    /*
    |--------------------------------------------------------------------------
    | Gallery images default config
    |--------------------------------------------------------------------------
    |
    */

    'images' => [
        'image' => [
            'size' => 1536
        ],
        'preview' => [
            'auto' => true,
            'width' => 800,
            'height' => 800,
            'size' => 768,
        ],
        'thumbnail' => [
            'auto' => true,
            'width' => 400,
            'height' => 400,
            'size' => 512,
        ],
        'watermark' => false
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
            "lang_title" => "gallery::default.galleries",
            "route" => "admin.gallery.index",
            "parent_route" => "admin.gallery",
            "icon" => "/elfcms/admin/modules/gallery/images/icons/gallery.png",
            "position" => 300,
            "submenu" => [
                [
                    "title" => "Galleries",
                    "lang_title" => "elfcms::default.settings",
                    "route" => "admin.gallery.settings.show",
                ]
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Access control
    |--------------------------------------------------------------------------
    |
    | Define access rules for admin panel pages.
    |
    */


    "access_routes" => [
        [
            "title" => "Galleries",
            "lang_title" => "gallery::default.galleries",
            "route" => "admin.gallery.index",
            "actions" => ["read", "write"],
        ],
        [
            "title" => "Galleries",
            "lang_title" => "elfcms::default.settings",
            "route" => "admin.gallery.settings.show",
            "actions" => ["read", "write"],
        ],
    ],

    'components' => [
        'slider' => [
            'class' => '\Elfcms\Gallery\View\Components\Slider',
            'options' => [
                'gallery' => false,
                'theme' => 'default',
            ],
        ],
        'gallery' => [
            'class' => '\Elfcms\Gallery\View\Components\Gallery',
            'options' => [
                'gallery' => false,
                'theme' => 'default',
            ],
        ],
    ],
];
