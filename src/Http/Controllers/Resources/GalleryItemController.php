<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
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
        $maxPosition = GalleryItem::max('position');
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
        if (empty($request->name) && !empty($request->file()['image'])) {
            $request->merge([
                'name' => $request->file()['image']->getClientOriginalName(),
            ]);

        }
        //return ['name'=>$request->name,'img'=>$request->file()];
        //return ['request'=>$request->all()];
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
        if (GalleryItem::where('slug',$request->slug)->count() > 0) {
            $request->merge([
                'slug' => $request->slug . '_' . time(),
            ]);
        }
        $validated = $request->validate([
            'category_id' => 'nullable',
            'name' => 'required',
            'slug' => 'required|unique:Elfcms\Gallery\Models\GalleryItem,slug',
            'image' => 'required|file|max:768',
            'preview' => 'nullable|file|max:512'
        ]);

        $preview_path = '';
        if (!empty($request->file()['preview'])) {
            $preview = $request->file()['preview']->store('public/gallery/items/preview');
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }
        $image_path = '';
        if (!empty($request->file()['image'])) {
            $image = $request->file()['image']->store('public/gallery/items/image');
            $image_path = str_ireplace('public/','/storage/',$image);
        }
        $thumbnail_path = '';
        if (!empty($request->file()['thumbnail'])) {
            $thumbnail = $request->file()['thumbnail']->store('public/gallery/items/thumbnail');
            $thumbnail_path = str_ireplace('public/','/storage/',$thumbnail);
        }

        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }
        $validated['image'] = $image_path;
        $validated['preview'] = $preview_path;
        $validated['thumbnail'] = $thumbnail_path;
        $validated['description'] = $request->description;
        $validated['additional_text'] = $request->additional_text;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['option'] = $request->option;
        $validated['position'] = $request->position;
        $validated['link'] = $request->link;
        $validated['gallery_id'] = $gallery->id;

        $galleryItem = GalleryItem::create($validated);

        if ($request->ajax()) {
            $data = view('gallery::admin.gallery.items.content.item',[
                'gallery' => $gallery,
                'item' => $galleryItem,
            ])->render();
            return [
                'result' => 'success',
                'data' => $data
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
    public function show(GalleryItem $galleryItem)
    {
        //
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
            if (empty($request->image && !empty($request->image_path))) {
                $request->merge([
                    'image' => $request->image_path,
                ]);
            }
            $validated = $request->validate([
                'name' => 'required',
                //'slug' => 'required|unique:Elfcms\Blog\Models\BlogPost,slug',
                'image' => 'required|file|max:512',
                'preview' => 'nullable|file|max:256',
                'thumbnail' => 'nullable|file|max:256'
            ]);

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
            $galleryItem->preview = $image_path;
            $galleryItem->preview = $preview_path;
            $galleryItem->preview = $thumbnail_path;
            $galleryItem->description = $request->description;
            $galleryItem->additional_text = $request->additional_text;
            $galleryItem->active = empty($request->active) ? 0 : 1;
            $galleryItem->option = $request->option;
            $galleryItem->link = $request->link;
            $galleryItem->position = intval($request->position);

            $galleryItem->save();

            if ($request->ajax()) {
                $data = view('gallery::admin.gallery.items.content.item',[
                    'gallery' => $gallery,
                    'item' => $galleryItem,
                ])->render();
                return [
                    'result' => 'success',
                    'data' => $data
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
