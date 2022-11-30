<a href="{{ route('admin.gallery.items.edit',['gallery'=>$gallery,'item'=>$item]) }}" class="gallery-item-tile" title="{{ __('basic::elf.edit') . ' ' . $item->name }}" style="order:{{$item->position}};" data-id="{{ $item->id }}">
    <img src="
        @if (!empty($item->thumbnail))
            {{asset($item->thumbnail)}}
        @elseif (!empty($item->preview))
            {{asset($item->preview)}}
        @else
            {{asset($item->image)}}
        @endif
    " alt="">
    <h5>{{ $item->name }}</h5>
</a>
