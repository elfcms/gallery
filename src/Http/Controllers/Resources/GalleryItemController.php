<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Elf\Image;
use Elfcms\Gallery\Models\Gallery;
use Elfcms\Gallery\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Gallery $gallery)
    {
        if ($request->ajax()) {
            return view('gallery::admin.gallery.items.content.index',[
                'page' => [
                    'title' => __('gallery::elf.items'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
            ]);
        }
        return view('gallery::admin.gallery.items.index',[
            'page' => [
                'title' => __('gallery::elf.items'),
                'current' => url()->current(),
            ],
            'gallery' => $gallery,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Gallery $gallery)
    {
        //Image::resize('/public/gallery/test/elka-9_1.webp', '/public/gallery/test/', height: 300);
        //dd(pathinfo('/dfgdfgdfg/sdfgdgs/sgfsdgsdfg.ASD',PATHINFO_EXTENSION));
        //$i = Image::watermarkToFile('/public/gallery/test/elka-9.jpg','/public/gallery/test/wm.png','/public/gallery/test/001.jpg',bottom:30, right: 10);
        //dd($i);
        $maxPosition = GalleryItem::where('gallery_id',$gallery->id)->max('position');
        $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        if ($request->ajax()) {
            return view('gallery::admin.gallery.items.content.create',[
                'page' => [
                    'title' => __('gallery::elf.create_item'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
                'position' => $position,
            ]);
        }
        return view('gallery::admin.gallery.items.create',[
            'page' => [
                'title' => __('gallery::elf.create_item'),
                'current' => url()->current(),
            ],
            'gallery' => $gallery,
            'position' => $position,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Gallery $gallery)
    {
        /* if ($request->ajax()) {
            return [
                'image' => $request->file()['image'],
                'name' => $request->file()['image']->getClientOriginalName(),
            ];
        } */
        $imageConfig = config('elfcms.gallery.images');
        $imageConfig['image']['size'] = $imageConfig['image']['size'] ?? 768;
        $imageConfig['preview']['size'] = $imageConfig['preview']['size'] ?? 512;
        $imageConfig['preview']['width'] = $imageConfig['preview']['width'] ?? 800;
        $imageConfig['preview']['height'] = $imageConfig['preview']['height'] ?? 600;
        $imageConfig['thumbnail']['size'] = $imageConfig['thumbnail']['size'] ?? 256;
        $imageConfig['thumbnail']['width'] = $imageConfig['thumbnail']['width'] ?? 400;
        $imageConfig['thumbnail']['height'] = $imageConfig['thumbnail']['height'] ?? 300;

        if (empty($request->name) && !empty($request->file()['image'])) {
            $request->merge([
                'name' => $request->file()['image']->getClientOriginalName(),
            ]);

        }

        if (empty($request->slug)) {
            $request->merge([
                'slug' => Str::slug($request->name),
            ]);
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
        }
        if (GalleryItem::withTrashed()->where('slug',$request->slug)->count() > 0) {
            $request->merge([
                'slug' => $request->slug . '_' . time(),
            ]);
        }

        $validated = $request->validate([
            'category_id' => 'nullable',
            'name' => 'required',
            'slug' => 'required|unique:Elfcms\Gallery\Models\GalleryItem,slug',
            'image' => 'required|file|max:'.$imageConfig['image']['size'],
            'preview' => 'nullable|file|max:'.$imageConfig['preview']['size']
        ]);

        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/gallery/items/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        $preview_path = '';
        if (!empty($request->file()['preview'])) {
            $preview = $request->file()['preview']->store('public/gallery/items/preview');
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['preview']['auto']) || $imageConfig['preview']['auto'] === true)) {
            $preview = Image::resize($image,'public/gallery/items/preview/',$imageConfig['preview']['width'],$imageConfig['preview']['height']);
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }
        $thumbnail_path = '';
        if (!empty($request->file()['thumbnail'])) {
            $thumbnail = $request->file()['thumbnail']->store('public/gallery/items/thumbnail');
            $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
        }
        elseif (!empty($image_path) && !empty($image) && (!isset($imageConfig['thumbnail']['auto']) || $imageConfig['thumbnail']['auto'] === true)) {
            $thumbnail = Image::resize($image,'public/gallery/items/thumbnail/',$imageConfig['thumbnail']['width'],$imageConfig['thumbnail']['height']);
            $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
        }

        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }

        $position = $request->position;
        if (empty($position)) {
            $maxPosition = GalleryItem::where('gallery_id',$gallery->id)->max('position');
            $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        }

        $validated['image'] = $image_path;
        $validated['preview'] = $preview_path;
        $validated['thumbnail'] = $thumbnail_path;
        $validated['description'] = $request->description;
        $validated['additional_text'] = $request->additional_text;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['option'] = $request->option;
        $validated['position'] = $position;
        $validated['link'] = $request->link;
        $validated['gallery_id'] = $gallery->id;

        $galleryItem = GalleryItem::create($validated);

        if (!empty($request->tags)) {
            foreach ($request->tags as $tagId) {
                $galleryItem->tags()->attach($tagId);
            }
        }

        if ($request->ajax()) {
            /* $data = view('gallery::admin.gallery.items.content.item',[
                'gallery' => $gallery,
                'item' => $galleryItem,
            ])->render();
            return [
                'result' => 'success',
                'data' => $data
            ]; */
            return [
                'result' => 'success',
                'message' => __('gallery::elf.item_edited_successfully'),
                'data' => $galleryItem->toArray(),
                /* [
                    'id' => $galleryItem->id,
                    'name' => $galleryItem->name,
                    'slug' => $galleryItem->slug,
                    'image' => $galleryItem->image,
                    'position' => $galleryItem->position,
                ], */
            ];
        }
        else {
            return redirect(route('admin.gallery.items',$gallery))->with('elementsuccess',__('gallery::elf.item_created_successfully'));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Gallery $gallery, GalleryItem $galleryItem)
    {
        if ($request->ajax()) {
            return view('gallery::admin.gallery.items.content.item',[
                'gallery' => $gallery,
                'item' => $galleryItem,
            ]);
        }
        return redirect(route('admin.gallery.items.edit',['gallery'=>$gallery,'galleryItem'=>$galleryItem]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Gallery $gallery, GalleryItem $galleryItem)
    {
        if ($request->ajax()) {
            return view('gallery::admin.gallery.items.content.edit',[
                'page' => [
                    'title' => __('gallery::elf.edit_item'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
                'item' => $galleryItem,
            ]);
        }
        return view('gallery::admin.gallery.items.edit',[
            'page' => [
                'title' => __('gallery::elf.edit_item'),
                'current' => url()->current(),
            ],
            'gallery' => $gallery,
            'item' => $galleryItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery, GalleryItem $galleryItem)
    {
        if ($request->notedit && $request->notedit == 1) {
            $galleryItem->active = empty($request->active) ? 0 : 1;

            $galleryItem->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::elf.item_edited_successfully'),
                    'data' => [
                        'id' => $galleryItem->id,
                        'name' => $galleryItem->name,
                        'slug' => $galleryItem->slug,
                        'image' => $galleryItem->image,
                        'position' => $galleryItem->position,
                    ]
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('gallery::elf.item_edited_successfully'));
        }
        elseif ($request->posedit && $request->posedit == 1) {
            $galleryItem->position = $request->position ?? 0;

            $galleryItem->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::elf.item_edited_successfully'),
                    'data' => [
                        'id' => $galleryItem->id,
                        'name' => $galleryItem->name,
                        'slug' => $galleryItem->slug,
                        'image' => $galleryItem->image,
                        'position' => $galleryItem->position,
                    ],
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('gallery::elf.gallery_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            if (GalleryItem::where('slug',$request->slug)->where('id','<>',$galleryItem->id)->first()) {
                $request->merge([
                    'slug' => $request->slug . '_' . time(),
                ]);
            }
            $validated = $request->validate([
                'name' => 'required',
                //'slug' => 'required|unique:Elfcms\Blog\Models\BlogPost,slug',
                'image' => 'nullable|file|max:512',
                'preview' => 'nullable|file|max:256',
                'thumbnail' => 'nullable|file|max:256'
            ]);

            if (empty($request->image) && empty($request->image_path)) {
                return redirect(route('admin.gallery.items.edit',['gallery'=>$gallery,'galleryItem'=>$galleryItem]))->withErrors(['image'=>__('validation.required',['Attribute'=>__('basic::elf.image')])]);
            }

            $image_path = $request->image_path;
            if (!empty($request->file()['image'])) {
                $image = $request->file()['image']->store('public/gallery/items/image');
                $image_path = str_ireplace('public/','/storage/',$image);
            }
            $preview_path = $request->preview_path;
            if (!empty($request->file()['preview'])) {
                $preview = $request->file()['preview']->store('public/gallery/items/preview');
                $preview_path = str_ireplace('public/','/storage/',$preview);
            }
            $thumbnail_path = $request->thumbnail_path;
            if (!empty($request->file()['thumbnail'])) {
                $thumbnail = $request->file()['thumbnail']->store('public/gallery/items/thumbnail');
                $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
            }

            //dd($image_path);
            //dd($preview_path);

            $galleryItem->name = $validated['name'];
            $galleryItem->slug = $request->slug;
            $galleryItem->image = $image_path;
            $galleryItem->preview = $preview_path;
            $galleryItem->thumbnail = $thumbnail_path;
            $galleryItem->description = $request->description;
            $galleryItem->additional_text = $request->additional_text;
            $galleryItem->active = empty($request->active) ? 0 : 1;
            $galleryItem->option = $request->option;
            $galleryItem->link = $request->link;
            $galleryItem->position = intval($request->position);

            $existTags = $galleryItem->tags->toArray();

            $newTags = $request->tags ? $request->tags : [];

            if (!empty($existTags)) {
                foreach ($existTags as $existTag) {
                    if (!in_array($existTag['id'],$newTags)) {
                        $galleryItem->tags()->detach($existTag['id']);
                    }
                    else {
                        $key = array_search($existTag['id'],$newTags);
                        unset($newTags[$key]);
                    }
                }
            }
            if (!empty($newTags)) {
                foreach ($newTags as $tagId) {
                    $galleryItem->tags()->attach($tagId);
                }
            }

            $galleryItem->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::elf.item_edited_successfully'),
                    'data' => $galleryItem->toArray(),
                ];
            }

            //return redirect(route('admin.gallery.items.edit',['gallery'=>$gallery,'galleryItem'=>$galleryItem]))->with('elementsuccess',__('gallery::elf.gallery_edited_successfully'));
            return redirect(route('admin.gallery.items',$gallery))->with('elementsuccess',__('gallery::elf.gallery_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleryItem $galleryItem)
    {
        //
    }
}
