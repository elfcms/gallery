<?php

namespace Elfcms\Gallery\View\Components;

use Elfcms\Gallery\Models\Gallery;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Slider extends Component
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
            $result = Gallery::find($gallery);
        }
        if (is_string($gallery)) {
            $result = Gallery::where('slug',$gallery)->with('items')->first();
        }
        $result->data = $result->sliderJson();
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
        if (View::exists('components.slider.' . $this->theme)) {
            return view('components.slider.' . $this->theme);
        }
        if (View::exists('gallery.components.slider.' . $this->theme)) {
            return view('gallery.components.slider.' . $this->theme);
        }
        if (View::exists('gallery::components.slider.' . $this->theme)) {
            return view('gallery::components.slider.' . $this->theme);
        }
        if (View::exists('elfcms.components.slider.' . $this->theme)) {
            return view('elfcms.components.slider.' . $this->theme);
        }
        if (View::exists('elfcms.gallery.components.slider.' . $this->theme)) {
            return view('elfcms.gallery.components.slider.' . $this->theme);
        }
        if (View::exists('elfcms::gallery.components.slider.' . $this->theme)) {
            return view('elfcms::gallery.components.slider.' . $this->theme);
        }
        if (View::exists($this->theme)) {
            return view($this->theme);
        }
        return null;
    }
}
