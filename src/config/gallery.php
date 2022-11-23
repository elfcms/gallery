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
    | Menu data
    |--------------------------------------------------------------------------
    |
    | Menu data of this package for admin panel
    |
    */

    "menu" => [
        [
            "title" => "Gallery",
            "lang_title" => "gallery::elf.gallery",
            "route" => "admin.gallery.index",
            "parent_route" => "admin.gallery.index",
            "icon" => "/vendor/elfcms/gallery/admin/images/icons/gallery.png",
            "position" => 120,
            "submenu" => []
        ],
    ],
];
