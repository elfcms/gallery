<?php

namespace Elfcms\Gallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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

    public function galleries()
    {
        return $this->hasMany(Gallery::class,'category_id');
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
