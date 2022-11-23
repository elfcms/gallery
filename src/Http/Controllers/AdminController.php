<?php

namespace Elfcms\Gallery\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function gallery()
    {
        /* $parentMenuData = config('elfcms.blog.menu');
        $menuData = [];
        foreach ($parentMenuData[0]['submenu'] as $key => $data) {
            $subdata = [];
            $text = '';

            if ($data['route'] == 'admin.blog.categories') {
                $categoriesCount = BlogCategory::count();
                $inactiveCategoriesCount = BlogCategory::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('basic::elf.categories'),
                    'value' => $categoriesCount . ' (' . $inactiveCategoriesCount . ' ' . __('basic::elf.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.posts') {
                $postsCount = BlogPost::count();
                $inactivePostsCount = BlogPost::where('active','<>',1)->count();
                $subdata[] = [
                    'title' => __('basic::elf.posts'),
                    'value' => $postsCount . ' (' . $inactivePostsCount . ' ' . __('basic::elf.inactive') . ')'
                ];
            }

            if ($data['route'] == 'admin.blog.comments') {
                $commentsCount = BlogComment::count();
                $subdata[] = [
                    'title' => __('basic::elf.comments'),
                    'value' => $commentsCount
                ];
            }

            if ($data['route'] == 'admin.blog.likes') {
                $likesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('basic::elf.likes'),
                    'value' => $likesCount
                ];
            }

            if ($data['route'] == 'admin.blog.votes') {
                $votesCount = BlogLike::count();
                $subdata[] = [
                    'title' => __('basic::elf.votes'),
                    'value' => $votesCount
                ];
            }

            if ($data['route'] == 'admin.blog.tags') {
                $tagsCount = BlogTag::count();
                $subdata[] = [
                    'title' => __('basic::elf.tags'),
                    'value' => $tagsCount
                ];
            }
            $menuData[$key] = $data;

            $menuData[$key]['subdata'] = $subdata;
            $menuData[$key]['text'] = $text;

        } */
        return view('gallery::admin.gallery.index',[
            'page' => [
                'title' => 'Gallery',
                'current' => url()->current(),
            ],
            //'menuData' => $menuData,
        ]);
    }

}
