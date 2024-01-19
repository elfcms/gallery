<?php

namespace Elfcms\Gallery\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\GalleryTag;
use Illuminate\Http\Request;

class GalleryTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return GalleryTag::all()->toJson();
        }
        $trend = 'asc';
        $order = 'id';
        if (!empty($request->trend) && $request->trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $tags = GalleryTag::orderBy($order, $trend)->paginate(30);

        return view('elfcms::admin.gallery.tags.index',[
            'page' => [
                'title' => 'Tags',
                'current' => url()->current(),
            ],
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('elfcms::admin.gallery.tags.create',[
            'page' => [
                'title' => 'Create tag',
                'current' => url()->current(),
            ],
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
        $validated = $request->validate([
            'name' => 'required|unique:Elfcms\Gallery\Models\GalleryTag,name'
        ]);
        $galleryTag = GalleryTag::create($validated);

        if ($request->ajax()) {
            $result = 'error';
            $message = __('elf.error_of_tag_created');
            $data = [];
            if ($galleryTag) {
                $result = 'success';
                $message = __('elf.tag_created_successfully');
                $data = ['id'=> $galleryTag->id];
            }
            return json_encode(['result'=>$result,'message'=>$message,'data'=>$data]);
        }

        return redirect(route('admin.gallery.tags.edit',$galleryTag->id))->with('tagcreated',__('elf.tag_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GalleryTag  $galleryTag
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, GalleryTag $galleryTag)
    {
        if ($request->ajax()) {
            return GalleryTag::find($galleryTag->id)->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GalleryTag  $galleryTag
     * @return \Illuminate\Http\Response
     */
    public function edit(GalleryTag $galleryTag)
    {
        return view('elfcms::admin.gallery.tags.edit',[
            'page' => [
                'title' => 'Edit tag #' . $galleryTag->id,
                'current' => url()->current(),
            ],
            'tag' => $galleryTag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GalleryTag  $galleryTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GalleryTag $galleryTag)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if (GalleryTag::where('name',$validated['name'])->where('id','<>',$galleryTag->id)->first()) {
            return redirect(route('admin.gallery.tags.edit',$galleryTag->id))->withErrors([
                'name' => 'Tag already exists'
            ]);
        }

        $galleryTag->name = $validated['name'];
        $galleryTag->save();

        return redirect(route('admin.gallery.tags.edit',$galleryTag->id))->with('tagedited',__('elf.tag_edited_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GalleryTag  $galleryTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleryTag $galleryTag)
    {
        if (!$galleryTag->delete()) {
            return redirect(route('admin.gallery.tags'))->withErrors(['tagdelerror'=>'Error of tag deleting']);
        }

        return redirect(route('admin.gallery.tags'))->with('tagdeleted','Tag deleted successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addNotExist(Request $request)
    {
        if ($request->ajax()) {
            //return $request->toArray();
            $validated = $request->validate([
                'name' => 'required'
            ]);

            $result = 'error';
            $message = __('elf.error_of_tag_created');
            $data = [];

            if ($tagByName = GalleryTag::where('name',$validated['name'])->first()) {
                $result = 'exist';
                $message = 'Tag already exist';
                $data = ['id'=> $tagByName->id,'name'=>$tagByName->name];
            }
            else {
                $galleryTag = GalleryTag::create($validated);

                if ($galleryTag) {
                    $result = 'success';
                    $message = __('elf.tag_created_successfully');
                    $data = ['id'=> $galleryTag->id,'name'=>$validated['name']];
                }
            }

            return json_encode(['result'=>$result,'message'=>$message,'data'=>$data]);
        }
    }
}
