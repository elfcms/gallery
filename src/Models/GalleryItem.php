<?php

namespace Elfcms\Gallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'name',
        'slug',
        'image',
        'preview',
        'description',
        'additional_text',
        'option',
        'active',
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
