<div class="gallery">
    {{$gallery->name}}
</div>
<div class="items">
    @foreach ($gallery->items as $item)
        {{$item->name}}<br>
    @endforeach
</div>
