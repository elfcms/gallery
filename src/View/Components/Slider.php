<?php

namespace Elfcms\Gallery\View\Components;

use Elfcms\Gallery\Models\Gallery;
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
        if (gettype($gallery) == 'string') {
            $result = Gallery::where('slug',$gallery)->with('items')->first();
        }
        $this->gallery = $result;
        //dd($this->gallery);
        //dd($this->menu->items('id','desc'));
        //dd($this->menu->items());
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('gallery::components.slider.'.$this->theme);
    }
}
