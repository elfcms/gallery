@if (!empty($gallery))
<div class="gallery-show-box">
    <h1>{{ $gallery->name }}</h1>
    @if(!empty($gallery->description))
    <p>{!! nl2br(e($gallery->description)) !!}</p>
    @endif
    @if(!empty($gallery->additional_text))
    <p>{!! nl2br(e($gallery->additional_text)) !!}</p>
    @endif
    @forelse ($gallery->items as $item)
        <div class="gallery-item-box">
            <img src="{{ $item->preview ??$item->image }}" alt="{{ $item->name }}">
            <h2>{{ $item->name }}</h2>
        </div>
    @empty

    @endforelse
</div>
@endif
