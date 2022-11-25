<?php

namespace Elfcms\Gallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function items()
    {
        return $this->belongsToMany(GalleryItem::class, 'gallery_item_tags', 'gallery_item_id', 'gallery_tag_id');
    }
}
