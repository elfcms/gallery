<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$adminPath = config('elfcms.elfcms.admin_path') ?? 'admin';
$adminPath = trim($adminPath,'/');

Route::group(['middleware'=>['web', 'locales','cookie']],function() use ($adminPath) {

    Route::prefix($adminPath . '/gallery')->name('admin.gallery.')->middleware('admin')->group(function() {

        Route::get('/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'show'])->name('settings.show');
        Route::post('/settings', [\Elfcms\Gallery\Http\Controllers\GallerySettingController::class,'save'])->name('settings.save');

        Route::post('/tags/addnotexist', [\Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class,'addNotExist'])->name('tags.addnotexist');
        Route::resource('/tags', \Elfcms\Gallery\Http\Controllers\Resources\GalleryTagController::class)
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
        Route::resource('/categories', Elfcms\Gallery\Http\Controllers\Resources\GalleryCategoryController::class)
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
        Route::resource('/', Elfcms\Gallery\Http\Controllers\Resources\GalleryController::class);
        Route::resource('/{gallery}/items', Elfcms\Gallery\Http\Controllers\Resources\GalleryItemController::class)
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
        Route::post('/{gallery}/items/group', [\Elfcms\Gallery\Http\Controllers\AdminController::class,'galleryItemGroupSave'])->name('items.groupSave');


    });

});
