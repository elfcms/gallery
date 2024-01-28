<?php

namespace Elfcms\Gallery\Models;

use Elfcms\Elfcms\Models\DefaultModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GallerySetting extends DefaultModel
{
    use HasFactory;

    protected $fillable = [
        'image_file_size',
        'is_preview',
        'preview_file_size',
        'preview_width',
        'preview_heigt',
        'is_thumbnail',
        'thumbnail_file_size',
        'thumbnail_width',
        'thumbnail_heigt',
        'is_watermark',
        'watermark',
        'watermark_first',
    ];

    static public $defaultString = [
        'image_file_size' => 1536,
        'is_preview' => 1,
        'preview_file_size' => 768,
        'preview_width' => 800,
        'preview_heigt' => 800,
        'is_thumbnail' => 1,
        'thumbnail_file_size' => 512,
        'thumbnail_width' => 400,
        'thumbnail_heigt' => 400,
        'is_watermark' => 0,
        'watermark' => null,
        'watermark_first' => 1,
    ];
}
