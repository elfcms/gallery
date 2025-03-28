<?php

namespace Elfcms\Gallery\Http\Requests\Admin;

use Elfcms\Elfcms\Aux\Image;
use Elfcms\Gallery\Models\GalleryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class GalleryItemUpdateRequest extends FormRequest
{
    private $imageConfig;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('elfcms::default.name'),
            'slug' => __('elfcms::default.slug'),
            'image' => __('elfcms::default.image'),
            'preview' => __('elfcms::default.preview'),
            'thumbnail' => __('elfcms::default.thumbnail'),
            'description' => __('elfcms::default.description'),
            'additional_text' => __('gallery::default.additional_text'),
            'option' => __('gallery::default.option'),
            'active' => __('elfcms::default.active'),
            'position' => __('elfcms::default.position'),
            'link' => __('elfcms::default.link'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            //'slug' => 'required|unique:Elfcms\Gallery\Models\GalleryItem,slug',
            'image' => 'nullable|file|max:' . ($this->imageConfig['image']['size'] ?? 1024),
            'preview' => 'nullable|file|max:' . ($this->imageConfig['preview']['size'] ?? 512),
            'thumbnail' => 'nullable|file|max:' . ($this->imageConfig['thumbnail']['size'] ?? 256),
            'description' => 'nullable',
            'additional_text' => 'nullable',
            'active' => 'nullable',
            'option' => 'nullable',
            'position' => 'nullable',
            'link' => 'nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $imageConfig = config('elfcms.gallery.images');
        $imageConfig['image']['size'] = $imageConfig['image']['size'] ?? 1024;
        $imageConfig['preview']['size'] = $imageConfig['preview']['size'] ?? 512;
        $imageConfig['preview']['width'] = $imageConfig['preview']['width'] ?? 800;
        $imageConfig['preview']['height'] = $imageConfig['preview']['height'] ?? 800;
        $imageConfig['thumbnail']['size'] = $imageConfig['thumbnail']['size'] ?? 256;
        $imageConfig['thumbnail']['width'] = $imageConfig['thumbnail']['width'] ?? 400;
        $imageConfig['thumbnail']['height'] = $imageConfig['thumbnail']['height'] ?? 400;

        $this->imageConfig = $imageConfig;

        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
        if (!empty($this->id) && GalleryItem::where('slug',$this->slug)->where('id','<>',$this->id)->first()) {
            $this->merge([
                'slug' => $this->slug . '_' . time(),
            ]);
        }

        $this->merge([
            'active' => empty($this->active) ? 0 : 1,
        ]);
    }

    protected function passedValidation ()
    {
        $image_path = $this->image_path;
        if (!empty($this->file()['image'])) {
            $image_path = $this->file()['image']->store('elfcms/gallery/items/image');
        }
        $preview_path = $this->preview_path;
        if (!empty($this->file()['preview'])) {
            $preview_path = $this->file()['preview']->store('elfcms/gallery/items/preview');
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['preview']['auto']) || $imageConfig['preview']['auto'] === true)) {
            $preview_path = Image::resize($image,'elfcms/gallery/items/preview/',$this->imageConfig['preview']['width'],$this->imageConfig['preview']['height']);
        }
        $thumbnail_path = $this->thumbnail_path;
        if (!empty($this->file()['thumbnail'])) {
            $thumbnail_path = $this->file()['thumbnail']->store('elfcms/gallery/items/thumbnail');
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['thumbnail']['auto']) || $imageConfig['thumbnail']['auto'] === true)) {
            $thumbnail_path = Image::resize($image,'elfcms/gallery/items/thumbnail/',$this->imageConfig['thumbnail']['width'],$this->imageConfig['thumbnail']['height']);
        }
        $this->files->remove('image');
        $this->convertedFiles = null;
        $this->replace([
            'slug' => $this->slug,
            'name' => $this->name,
            'image' => $image_path,
            'preview' => $preview_path,
            'thumbnail' => $thumbnail_path,
            'description' => $this->description,
            'additional_text' => $this->additional_text,
            'active' => $this->active,
            'option' => $this->option,
            'position' => intval($this->option),
            'link' => $this->link,
        ]);
    }
}
