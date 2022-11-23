<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = Config::get('elfcms.basic.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie','start']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        /* Route::name('gallery.')->group(function() use ($adminPath) {
            Route::get($adminPath . '/gallery', [\Elfcms\Gallery\Http\Controllers\AdminController::class,'gallery'])->name('index');
        }); */
        /* Route::get($adminPath . '/ajax/json/simplebox/datatypes',function(Request $request){
            $result = [];
            if ($request->ajax()) {
                $result = SimpleboxDataType::all()->toArray();
                $result = json_encode($result);
            }
            return $result;
        }); */
        Route::resource($adminPath . '/gallery/categories', Elfcms\Gallery\Http\Controllers\Resources\GalleryCategoryController::class)->names([
            'index' => 'gallery.categories',
            'create' => 'gallery.categories.create',
            'edit' => 'gallery.categories.edit',
            'store' => 'gallery.categories.store',
            'show' => 'gallery.categories.show',
            'edit' => 'gallery.categories.edit',
            'update' => 'gallery.categories.update',
            'destroy' => 'gallery.categories.destroy'
        ]);
        Route::resource($adminPath . '/gallery', Elfcms\Gallery\Http\Controllers\Resources\GalleryController::class);
        Route::resource($adminPath . '/gallery/{gallery}/items', Elfcms\Gallery\Http\Controllers\Resources\GalleryItemController::class)->names(['index' => 'item']);

    });

});
