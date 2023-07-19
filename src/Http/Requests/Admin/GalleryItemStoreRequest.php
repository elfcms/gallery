<?php

namespace Elfcms\Gallery\Http\Requests\Admin;

use Elfcms\Basic\Elf\Image;
use Elfcms\Gallery\Models\GalleryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'name' => __('basic::elf.name'),
            'slug' => __('basic::elf.slug'),
            'image' => __('basic::elf.image'),
            'preview' => __('basic::elf.preview'),
            'thumbnail' => __('basic::elf.thumbnail'),
            'description' => __('basic::elf.description'),
            'additional_text' => __('gallery::elf.additional_text'),
            'option' => __('gallery::elf.option'),
            'active' => __('basic::elf.active'),
            'link' => __('basic::elf.link'),
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
            //'position' => 'nullable',
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
        $image_path = '';
        if (!empty($this->file()['image'])) {
            //return $this->file();
            $image = $this->file()['image']->store('public/gallery/items/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        $preview_path = '';
        if (!empty($this->file()['preview'])) {
            $preview = $this->file()['preview']->store('public/gallery/items/preview');
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['preview']['auto']) || $imageConfig['preview']['auto'] === true)) {
            $preview = Image::resize($image,'public/gallery/items/preview/',$this->imageConfig['preview']['width'],$this->imageConfig['preview']['height']);
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }
        $thumbnail_path = '';
        if (!empty($this->file()['thumbnail'])) {
            $thumbnail = $this->file()['thumbnail']->store('public/gallery/items/thumbnail');
            $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['thumbnail']['auto']) || $imageConfig['thumbnail']['auto'] === true)) {
            $thumbnail = Image::resize($image,'public/gallery/items/thumbnail/',$this->imageConfig['thumbnail']['width'],$this->imageConfig['thumbnail']['height']);
            $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
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
                throw new HttpResponseException(response()->json($instance->errors(), 422));
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
        //dd($validator->errors()->messages());
        /* if($this->wantsJson())
        {
            $response = response()->json([
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $validator->errors()
            ]);
        }else{
            $response = redirect()
                ->route('guest.login')
                ->with('message', 'Ops! Some errors occurred')
                ->withErrors($validator);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl()); */
    }
}
