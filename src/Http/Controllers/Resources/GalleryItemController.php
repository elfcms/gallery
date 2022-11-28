<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\Gallery;
use Elfcms\Gallery\Models\GalleryItem;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {
        //
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
    public function edit(GalleryItem $galleryItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GalleryItem  $galleryItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GalleryItem $galleryItem)
    {
        //
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
