<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\Gallery;
use Elfcms\Gallery\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $nullCategory->name = '<' . __('gallery::default.no_category') . '>';
        $nullCategory->galleries = Gallery::where('category_id',null)->get();
        $categories->push($nullCategory); */
        //dd($galleries);
        return view('elfcms::admin.gallery.index',[
            'page' => [
                'title' => __('gallery::default.galleries'),
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
        return view('elfcms::admin.gallery.create',[
            'page' => [
                'title' => __('gallery::default.create_gallery'),
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
        $request->merge([
            'slug' => Str::slug($request->slug),
        ]);
        $validated = $request->validate([
            'category_id' => 'nullable',
            'name' => 'required',
            'slug' => 'required|unique:Elfcms\Gallery\Models\Gallery,slug',
            'preview' => 'nullable|file|max:512'
        ]);

        $preview_path = '';
        if (!empty($request->file()['preview'])) {
            $preview = $request->file()['preview']->store('public/elfcms/gallery/preview');
            $preview_path = str_ireplace('public/','/storage/',$preview);
        }

        if (empty($validated['category_id'])) {
            $validated['category_id'] = null;
        }
        $validated['preview'] = $preview_path;
        $validated['description'] = $request->description;
        $validated['additional_text'] = $request->additional_text;
        $validated['active'] = empty($request->active) ? 0 : 1;
        $validated['option'] = $request->option;

        $gallery = Gallery::create($validated);

        return redirect(route('admin.gallery.edit',$gallery->slug))->with('gallerysuccess',__('gallery::default.gallery_created_successfully'));
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
        $categories = GalleryCategory::active()->get();
        return view('elfcms::admin.gallery.edit',[
            'page' => [
                'title' => __('gallery::default.edit_gallery'),
                'current' => url()->current(),
            ],
            'categories' => $categories,
            'gallery' => $gallery,
        ]);
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
        if ($request->notedit && $request->notedit == 1) {
            $gallery->active = empty($request->active) ? 0 : 1;

            $gallery->save();

            return redirect(route('admin.gallery.index'))->with('gallerysuccess',__('gallery::default.gallery_edited_successfully'));
        }
        else {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
            $validated = $request->validate([
                'category_id' => 'nullable',
                'name' => 'required',
                //'slug' => 'required|unique:Elfcms\Blog\Models\BlogPost,slug',
                //'image' => 'nullable|file|max:512',
                'preview' => 'nullable|file|max:256'
            ]);
            if (Gallery::where('slug',$request->slug)->where('id','<>',$gallery->id)->first()) {
                return redirect(route('admin.gallery.edit',$gallery->slug))->withErrors([
                    'slug' => 'Gallery already exists'
                ]);
            }

            $preview_path = $request->preview_path;
            if (!empty($request->file()['preview'])) {
                $preview = $request->file()['preview']->store('public/elfcms/gallery/preview');
                $preview_path = str_ireplace('public/','/storage/',$preview);
            }

            //dd($image_path);
            //dd($preview_path);

            $gallery->category_id = empty($validated['category_id']) ? null : $validated['category_id'];
            $gallery->name = $validated['name'];
            $gallery->slug = $request->slug;
            $gallery->preview = $preview_path;
            $gallery->description = $request->description;
            $gallery->additional_text = $request->additional_text;
            $gallery->active = empty($request->active) ? 0 : 1;
            $gallery->option = $request->option;

            $gallery->save();

            return redirect(route('admin.gallery.edit',$gallery->slug))->with('gallerysuccess',__('gallery::default.gallery_edited_successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        if (!$gallery->delete()) {
            return redirect(route('admin.gallery.index'))->withErrors(['gallerysuccess'=>'Error of post deleting']);
        }

        return redirect(route('admin.gallery.index'))->with('gallerysuccess',__('gallery::default.gallery_deleted_successfully'));
    }
}
