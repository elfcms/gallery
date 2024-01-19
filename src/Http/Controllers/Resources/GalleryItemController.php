<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Aux\Image;
use Elfcms\Gallery\Http\Requests\Admin\GalleryItemStoreRequest;
use Elfcms\Gallery\Http\Requests\Admin\GalleryItemUpdateRequest;
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
            return view('elfcms::admin.gallery.items.content.index',[
                'page' => [
                    'title' => __('gallery::default.items'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
            ]);
        }
        return view('elfcms::admin.gallery.items.index',[
            'page' => [
                'title' => __('gallery::default.items'),
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
        $maxPosition = GalleryItem::where('gallery_id',$gallery->id)->max('position');
        $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        if ($request->ajax()) {
            return view('elfcms::admin.gallery.items.content.create',[
                'page' => [
                    'title' => __('gallery::default.create_item'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
                'position' => $position,
            ]);
        }
        return view('elfcms::admin.gallery.items.create',[
            'page' => [
                'title' => __('gallery::default.create_item'),
                'current' => url()->current(),
            ],
            'gallery' => $gallery,
            'position' => $position,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Elfcms\Gallery\Http\Requests\Admin\GalleryItemStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryItemStoreRequest $request, Gallery $gallery)
    {
        $request->validated();

        $validated = $request->all();

        $validated['gallery_id'] = $gallery->id;
        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }
        $position = $request->position;
        if (empty($position)) {
            $maxPosition = GalleryItem::where('gallery_id',$gallery->id)->max('position');
            $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        }
        $validated['position'] = $position;

        $galleryItem = GalleryItem::create($validated);

        if (!empty($request->tags)) {
            foreach ($request->tags as $tagId) {
                $galleryItem->tags()->attach($tagId);
            }
        }

        if ($request->ajax()) {
            return [
                'result' => 'success',
                'message' => __('gallery::default.item_edited_successfully'),
                'data' => $galleryItem->toArray(),
            ];
        }
        else {
            return redirect(route('admin.gallery.items',$gallery))->with('elementsuccess',__('gallery::default.item_created_successfully'));
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
            return view('elfcms::admin.gallery.items.content.item',[
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
            return view('elfcms::admin.gallery.items.content.edit',[
                'page' => [
                    'title' => __('gallery::default.edit_item'),
                    'current' => url()->current(),
                ],
                'gallery' => $gallery,
                'item' => $galleryItem,
            ]);
        }
        return view('elfcms::admin.gallery.items.edit',[
            'page' => [
                'title' => __('gallery::default.edit_item'),
                'current' => url()->current(),
            ],
            'gallery' => $gallery,
            'item' => $galleryItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Elfcms\Gallery\Http\Requests\Admin\GalleryItemUpdateRequest  $request
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryItemUpdateRequest $request, Gallery $gallery, GalleryItem $galleryItem)
    {
        if ($request->notedit && $request->notedit == 1) {
            $galleryItem->active = empty($request->active) ? 0 : 1;

            $galleryItem->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::default.item_edited_successfully'),
                    'data' => [
                        'id' => $galleryItem->id,
                        'name' => $galleryItem->name,
                        'slug' => $galleryItem->slug,
                        'image' => $galleryItem->image,
                        'position' => $galleryItem->position,
                    ]
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('gallery::default.item_edited_successfully'));
        }
        elseif ($request->posedit && $request->posedit == 1) {
            $galleryItem->position = $request->position ?? 0;

            $galleryItem->save();

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::default.item_edited_successfully'),
                    'data' => [
                        'id' => $galleryItem->id,
                        'name' => $galleryItem->name,
                        'slug' => $galleryItem->slug,
                        'image' => $galleryItem->image,
                        'position' => $galleryItem->position,
                    ],
                ];
            }

            return redirect(route('admin.gallery.items'))->with('elementsuccess',__('gallery::default.gallery_edited_successfully'));
        }
        else {
            $request->validated();

            $validated = $request->all();

            $validated['position'] = intval($request->position);

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

            $galleryItem->update($validated);

            if ($request->ajax()) {
                return [
                    'result' => 'success',
                    'message' => __('gallery::default.item_edited_successfully'),
                    'data' => $galleryItem->toArray(),
                ];
            }

            return redirect(route('admin.gallery.items',$gallery))->with('elementsuccess',__('gallery::default.gallery_edited_successfully'));
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
