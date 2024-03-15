<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.elfcms.admin_path') ?? '/admin';

Route::group(['middleware'=>['web','cookie']],function() use ($adminPath) {

    Route::name('admin.')->middleware('admin')->group(function() use ($adminPath) {

        Route::get($adminPath . '/gallery/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'show'])->name('gallery.settings.show');
        Route::post($adminPath . '/gallery/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'save'])->name('gallery.settings.save');

        Route::post($adminPath . '/gallery/tags/addnotexist', [\Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class,'addNotExist'])->name('gallery.tags.addnotexist');
        Route::resource($adminPath . '/gallery/tags', \Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class)
        ->parameters(['tags'=>'galleryTag'])
        ->names([
            'index' => 'gallery.tags',
            'create' => 'gallery.tags.create',
            'edit' => 'gallery.tags.edit',
            'store' => 'gallery.tags.store',
            'show' => 'gallery.tags.show',
            'edit' => 'gallery.tags.edit',
            'update' => 'gallery.tags.update',
            'destroy' => 'gallery.tags.destroy'
        ]);
        Route::resource($adminPath . '/gallery/categories', Elfcms\Gallery\Http\Controllers\Resources\GalleryCategoryController::class)
        ->names([
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
        Route::resource($adminPath . '/gallery/{gallery}/items', Elfcms\Gallery\Http\Controllers\Resources\GalleryItemController::class)
        ->parameters(['items'=>'galleryItem'])
        ->names([
            'index' => 'gallery.items',
            'create' => 'gallery.items.create',
            'edit' => 'gallery.items.edit',
            'store' => 'gallery.items.store',
            'show' => 'gallery.items.show',
            'edit' => 'gallery.items.edit',
            'update' => 'gallery.items.update',
            'destroy' => 'gallery.items.destroy',
        ]);
        Route::post($adminPath . '/gallery/{gallery}/items/group', [\Elfcms\Gallery\Http\Controllers\AdminController::class,'galleryItemGroupSave'])->name('gallery.items.groupSave');


    });

});
