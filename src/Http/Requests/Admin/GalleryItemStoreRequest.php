<?php

namespace Elfcms\Gallery\Http\Requests\Admin;

use Elfcms\Elfcms\Aux\Image;
use Elfcms\Gallery\Models\GalleryItem;
use Elfcms\Gallery\Models\GallerySetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class GalleryItemStoreRequest extends FormRequest
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
            'slug' => 'required|unique:Elfcms\Gallery\Models\GalleryItem,slug',
            'image' => 'required|file|max:' . ($this->imageConfig['image']['size'] ?? 1024),
            'preview' => 'nullable|file|max:' . ($this->imageConfig['preview']['size'] ?? 512),
            'thumbnail' => 'nullable|file|max:' . ($this->imageConfig['thumbnail']['size'] ?? 256),
            'description' => 'nullable',
            'additional_text' => 'nullable',
            'active' => 'nullable',
            'option' => 'nullable',
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

        if (empty($this->name) && !empty($this->file()['image'])) {
            $this->merge([
                'name' => $this->file()['image']->getClientOriginalName(),
            ]);

        }

        if (empty($this->slug)) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
        else {
            $this->merge([
                'slug' => Str::slug($this->slug),
            ]);
        }
        if (GalleryItem::withTrashed()->where('slug',$this->slug)->count() > 0) {
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
        $params = GallerySetting::getParams();
        $params['watermark'] = str_ireplace('/storage/','public/',$params['watermark']);
        $position = explode(',',$params['watermark_position']);
        if (empty($position[0]) || !in_array(trim($position[0]),['left','center','right'])) {
            $position_h = 'center';
        }
        else {
            $position_h = trim($position[0]);
        }
        if (empty($position[1]) || !in_array(trim($position[1]),['top','center','bottom'])) {
            $position_v = 'center';
        }
        else {
            $position_v = trim($position[1]);
        }
        $stampedDir = 'public/elfcms/gallery/items/stamped';

        /* Image */
        $image_path = '';
        if (!empty($this->file()['image'])) {
            $image = $this->file()['image']->store('public/elfcms/gallery/items/image');
            if ($params['is_watermark'] && !empty($params['watermark'])) {
                if (!is_dir(Storage::path($stampedDir . '/image'))) {
                    Storage::makeDirectory($stampedDir . '/image');
                }
                $newImage = Image::stamp(Storage::path($image), $params['watermark'], $params['watermark_size'], $params['watermark_indent_h'], $params['watermark_indent_v'], $position_h, $position_v, savePath: $stampedDir . '/image');
                if ($newImage) $image = $newImage;
            }
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        //dd($extra);

        /* Preview */
        $preview_path = '';
        if ($params['is_preview']){
            if (!empty($this->file()['preview'])) {
                $preview = $this->file()['preview']->store('public/elfcms/gallery/items/preview');
                if ($params['is_watermark'] && !empty($params['watermark'])) {
                    $newPreview = Image::stamp($image,$params['watermark'],$params['watermark_size'],$params['watermark_indent_h'],$params['watermark_indent_v'],$position_h,$position_v,savePath:'public/elfcms/gallery/items/preview/stamped');
                    if ($newPreview) $preview = $newPreview;
                }
                $preview_path = str_ireplace('public/','/storage/',$preview);
            }
            elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['preview']['auto']) || $imageConfig['preview']['auto'] === true)) {
                $preview = Image::resize($image,'public/elfcms/gallery/items/preview/',$this->imageConfig['preview']['width'],$this->imageConfig['preview']['height']);
                /* if ($params['is_watermark'] && !empty($params['watermark'])) {
                    $image = Image::stamp($image,$params['watermark'],$params['watermark_size'],$params['watermark_indent_h'],$params['watermark_indent_v'],$position_h,$position_v,savePath:'public/elfcms/gallery/items/preview/stamped');
                } */
                $preview_path = str_ireplace('public/','/storage/',$preview);
            }
        }

        /* Thumbnail */
        $thumbnail_path = '';
        if ($params['is_thumbnail']) {
            if (!empty($this->file()['thumbnail'])) {
                $thumbnail = $this->file()['thumbnail']->store('public/elfcms/gallery/items/thumbnail');
                if ($params['is_watermark'] && !empty($params['watermark'])) {
                    $newThumb = Image::stamp($image,$params['watermark'],$params['watermark_size'],$params['watermark_indent_h'],$params['watermark_indent_v'],$position_h,$position_v,savePath:'public/elfcms/gallery/items/thumbnail/stamped');
                    if($newThumb) $thumbnail = $newThumb;
                }
                $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
            }
            elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['thumbnail']['auto']) || $imageConfig['thumbnail']['auto'] === true)) {
                $thumbnail = Image::resize($image,'public/elfcms/gallery/items/thumbnail/',$this->imageConfig['thumbnail']['width'],$this->imageConfig['thumbnail']['height']);
                /* if ($params['is_watermark'] && !empty($params['watermark'])) {
                    $image = Image::stamp($image,$params['watermark'],$params['watermark_size'],$params['watermark_indent_h'],$params['watermark_indent_v'],$position_h,$position_v,savePath:'public/elfcms/gallery/items/thumbnail/stamped');
                } */
                $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
            }
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
            'link' => $this->link,
        ]);
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null) {
        if($this->ajax()) {
            $instance = $this->getValidatorInstance();
            if ($instance->fails()) {
            //dd($instance);
                $errorText = '';
                foreach($instance->errors()->toArray() as $k=>$text){
                    $errorText .= implode('; ', $text);
                }
                throw new HttpResponseException(response()->json(['result'=>'error','message' => $errorText], 422));
            }
        }
        else {
            parent::validated($key, $default);
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        //
    }
}
