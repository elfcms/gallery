<?php

namespace Elfcms\Gallery\View\Components;

use Elfcms\Gallery\Models\Gallery as GalleryModel;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Gallery extends Component
{
    public $gallery, $theme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($gallery, $theme='default')
    {
        $result = $gallery;
        if (is_numeric($gallery)) {
            $gallery = intval($gallery);
            $result = GalleryModel::find($gallery);
        }
        if (is_string($gallery)) {
            $result = GalleryModel::where('slug',$gallery)->with('items')->first();
        }
        $this->gallery = $result;
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.gallery.' . $this->theme)) {
            return view('components.gallery.' . $this->theme);
        }
        if (View::exists('gallery.components.gallery.' . $this->theme)) {
            return view('gallery.components.gallery.' . $this->theme);
        }
        if (View::exists('gallery::components.gallery.' . $this->theme)) {
            return view('gallery::components.gallery.' . $this->theme);
        }
        if (View::exists('elfcms.components.gallery.' . $this->theme)) {
            return view('elfcms.components.gallery.' . $this->theme);
        }
        if (View::exists('elfcms.gallery.components.gallery.' . $this->theme)) {
            return view('elfcms.gallery.components.gallery.' . $this->theme);
        }
        if (View::exists('elfcms.modules.gallery.components.gallery.' . $this->theme)) {
            return view('elfcms.modules.gallery.components.gallery.' . $this->theme);
        }
        if (View::exists('elfcms::gallery.components.gallery.' . $this->theme)) {
            return view('elfcms::gallery.components.gallery.' . $this->theme);
        }
        if (View::exists($this->theme)) {
            return view($this->theme);
        }
        return null;
    }
}
