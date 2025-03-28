<a href="{{ route('admin.gallery.items.edit',['gallery'=>$gallery,'galleryItem'=>$item]) }}" class="gallery-item-tile gallery-item-element" title="{{ __('elfcms::default.edit') . ' ' . $item->name }}" style="order:{{$item->position}};" data-id="{{ $item->id }}" data-slug="{{ $item->slug }}">
    <img src="
        @if (!empty($item->thumbnail))
            {{asset(file_path($item->thumbnail))}}
        @elseif (!empty($item->preview))
            {{asset(file_path($item->preview))}}
        @else
            {{asset(file_path($item->image))}}
        @endif
    " alt="">
    <h5>{{ $item->name }}</h5>
    <div class="delete-item-box" title="{{ __('elfcms::default.delete') }}">
        <input type="checkbox" name="item[{{$item->id}}][delete]" id="item_{{$item->id}}_delete" data-field="delete" onclick="event.stopPropagation()">
        <i></i>
    </div>
    <input type="hidden" name="item[{{$item->id}}][position]" value="{{$item->position}}" data-field="position">
</a>
