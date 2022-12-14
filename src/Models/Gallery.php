<?php

namespace Elfcms\Gallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class,'category_id');
    }

    public function items()
    {
        return $this->hasMany(GalleryItem::class)->orderBy('position');
    }

    public function sliderData()
    {
        $this->with('items');
        $result = [];
        foreach ($this->items as $item) {
            $result[] = [
                'title' => $item['name'],
                'description' => $item['description'],
                'img' => empty($item['preview']) ? $item['image'] : $item['preview'],
                'full' => $item['image'],
                'thumb' => empty($item['thumbnail']) ? (empty($item['preview']) ? $item['image'] : $item['preview']) : $item['thumbnail'],
            ];
        }
        return $result;
    }

    public function sliderJson()
    {
        return json_encode($this->sliderData(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
