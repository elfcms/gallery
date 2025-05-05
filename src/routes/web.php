<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.elfcms.admin_path') ?? 'admin';
$adminPath = trim($adminPath,'/');

Route::group(['middleware'=>['web', 'locales','cookie']],function() use ($adminPath) {

    Route::prefix($adminPath)->name('admin.gallery.')->middleware('admin')->group(function() {

        Route::get('/gallery/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'show'])->name('settings.show');
        Route::post('/gallery/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'save'])->name('settings.save');

        Route::post('/gallery/tags/addnotexist', [\Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class,'addNotExist'])->name('tags.addnotexist');
        Route::resource('/gallery/tags', \Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class)
        ->parameters(['tags'=>'galleryTag'])
        ->names([
            'index' => 'tags',
            'create' => 'tags.create',
            'edit' => 'tags.edit',
            'store' => 'tags.store',
            'show' => 'tags.show',
            'edit' => 'tags.edit',
            'update' => 'tags.update',
            'destroy' => 'tags.destroy'
        ]);
        Route::resource('/gallery/categories', Elfcms\Gallery\Http\Controllers\Resources\GalleryCategoryController::class)
        ->names([
            'index' => 'categories',
            'create' => 'categories.create',
            'edit' => 'categories.edit',
            'store' => 'categories.store',
            'show' => 'categories.show',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy'
        ]);
        Route::resource('/gallery', Elfcms\Gallery\Http\Controllers\Resources\GalleryController::class)->names([
            'index' => 'index',
            'create' => 'create',
            'edit' => 'edit',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ]);
        Route::resource('/gallery/{gallery}/items', Elfcms\Gallery\Http\Controllers\Resources\GalleryItemController::class)
        ->parameters(['items'=>'galleryItem'])
        ->names([
            'index' => 'items',
            'create' => 'items.create',
            'edit' => 'items.edit',
            'store' => 'items.store',
            'show' => 'items.show',
            'edit' => 'items.edit',
            'update' => 'items.update',
            'destroy' => 'items.destroy',
        ]);
        Route::post('/gallery/{gallery}/items/group', [\Elfcms\Gallery\Http\Controllers\AdminController::class,'galleryItemGroupSave'])->name('items.groupSave');


    });

});
