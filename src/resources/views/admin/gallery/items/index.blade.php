@extends('elfcms::admin.layouts.main')
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('pagecontent')

<div class="gallery-info-box">
    <div class="gallery-preview-box">
        <img src="{{ file_path($gallery->preview) }}" alt="">
    </div>
    <div class="gallery-data-box">
        <h2>{{ $gallery->name }}</h2>
        <div class="gallery-description">{{ $gallery->description }}</div>
        <div class="gallery-addtitional-text">{{ $gallery->addtitional_text }}</div>
    </div>
    <div class="gallery-edit-button-box">
        <a href="{{ route('admin.gallery.edit',$gallery) }}" class="button round-button theme-button">
            <span class="button-collapsed-text">{{__('elfcms::default.edit')}}</span>
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
        </a>
    </div>
</div>
<div class="gallery-items-box">
    @include('elfcms::admin.gallery.items.content.index')
</div>

@endsection
