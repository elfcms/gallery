<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\Gallery;
use Elfcms\Gallery\Models\GalleryCategory;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trend = 'asc';
        $order = 'id';
        $search = $request->search ?? '';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        if (!empty($request->count)) {
            $count = intval($request->count);
        }
        if (empty($count)) {
            $count = 30;
        }

        $category = null;
        if (!empty ($search)) {
            $galleries = Gallery::where('name','like',"%{$search}%")->orderBy($order, $trend)->with('category')->withCount('items')->paginate($count);
        }
        elseif (!empty($request->category)) {
            $galleries = Gallery::where('category_id',$request->category)->orderBy($order, $trend)->with('category')->withCount('items')->paginate($count);
            $category = GalleryCategory::find($request->category);
        }
        else {
            $galleries = Gallery::orderBy($order, $trend)->with('category')->withCount('items')->paginate($count);
        }
        /* $nullCategory = new GalleryCategory;//::where('id',null)->with('galleries')->get();
        $nullCategory->id = null;
        $nullCategory->name = '<' . __('gallery::elf.no_category') . '>';
        $nullCategory->galleries = Gallery::where('category_id',null)->get();
        $categories->push($nullCategory); */
        //dd($galleries);
        return view('gallery::admin.gallery.index',[
            'page' => [
                'title' => __('gallery::elf.galleries'),
                'current' => url()->current(),
            ],
            'galleries' => $galleries,
            'search' => $search,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = GalleryCategory::active()->get();
        return view('gallery::admin.gallery.create',[
            'page' => [
                'title' => __('gallery::elf.create_gallery'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'category_id' => $request->category_id
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
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
