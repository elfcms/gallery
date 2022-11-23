<?php

namespace Elfcms\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\GalleryCategory;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function gallery()
    {
        /* $categories = GalleryCategory::where('active',1)->with('galleries')->get();
        //dd($categories);
        return view('gallery::admin.gallery.index',[
            'page' => [
                'title' => 'Gallery',
                'current' => url()->current(),
            ],
            'categories' => $categories,
        ]); */
    }

}
