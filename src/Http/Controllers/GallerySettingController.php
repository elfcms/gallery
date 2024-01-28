<?php

namespace Elfcms\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Gallery\Models\GallerySetting;
use Illuminate\Http\Request;

class GallerySettingController extends Controller
{
    public function show() {
        $settings = GallerySetting::orderBy('id','desc')->first() ?? new GallerySetting();
        return view('elfcms::admin.gallery.settings',[
            'page' => [
                'title' => __('gallery::default.gallery') . ': '. __('elfcms::default.settings'),
                'current' => url()->current(),
            ],
            'settings' => $settings,
        ]);
    }

    public function save(Request $request) {
        $result = false;

        $data = $request->all();
        $data['is_preview'] = $data['is_preview'] ?? 0;
        $data['is_thumbnail'] = $data['is_thumbnail'] ?? 0;
        $data['is_watermark'] = $data['is_watermark'] ?? 0;
        $data['watermark_first'] = $data['watermark_first'] ?? 0;
        //dd($data);
        //if (!empty($data['watermark_path'])) {
            $image_path = $data['watermark_path'];
            if (!empty($request->file()['watermark'])) {
                $image = $request->file()['watermark']->store('public/gallery/watermarks');
                $image_path = str_ireplace('public/','/storage/',$image);
            }
            $data['watermark'] = $image_path;
        //}
        //unset($data['watermark']);

        $settings = GallerySetting::orderBy('id','desc')->first();

        if ($settings) {
            $result = $settings->update($data);
        }
        else {
            $result = GallerySetting::create($data);
        }

        if ($result) {
            return redirect(route('admin.gallery.settings.show'))->with('success',__('elfcms::default.settings_edited_successfully'));
        }

        return redirect(route('admin.gallery.settings.show'))->withErrors(['error'=>__('elfcms::default.error_saving_data')]);
    }
}
