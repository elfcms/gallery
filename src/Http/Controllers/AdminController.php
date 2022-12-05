<?php

namespace Elfcms\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\Gallery;
use Elfcms\Gallery\Models\GalleryCategory;
use Elfcms\Gallery\Models\GalleryItem;
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

    public function galleryItemGroupSave(Request $request, Gallery $gallery)
    {
        $result = [
            'result' => 'success',
            'message' => __('gallery::elf.gallery_edited_successfully'),
            'data' => null
        ];
        if (empty($request->item)) {
            $result['message'] = 'Error';
            $result['result'] = 'error';

            return $result;
        }
        $toUpdate = [];
        $toDelete = [];
        foreach ($request->item as $itemId => $itemData) {
            if (!empty($itemData['delete'])) {
                $toDelete[] = $itemId;
            }
            elseif (!empty($itemData['position'])) {
                /* $toUpdate[] = [
                    'id' => $itemId,
                    'gallery_id' => $gallery->id,
                    'position' => $itemData['position']
                ]; */
                GalleryItem::where('id',$itemId)->where('gallery_id',$gallery->id)->update(['position'=>$itemData['position']]);
            }
        }
        if (!empty($toDelete)) {
            GalleryItem::destroy($toDelete);
        }
        /* if (!empty($toUpdate)) {
            GalleryItem::upsert(
                $toUpdate,
                ['id'],
                ['position']
            )->dd();
        } */
        return $result;//[$toUpdate,$toDelete];
    }

    public function test(Request $request)
    {
        return [
            'result' => 'success',
            'request' => $request->all(),
            'method' => $request->method(),
            'files' => $request->file(),
        ];
    }

    public function tf(Request $request)
    {
        return view('gallery::admin.gallery.tf',[
            'page' => [
                'title' => '',
                'current' => url()->current(),
            ],
        ]);
    }

}
