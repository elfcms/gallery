@extends('elfcms::admin.layouts.gallery')
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('gallery-content')

@if (Session::has('elementsuccess'))
<div class="alert alert-alternate">{{ Session::get('elementsuccess') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="gallery-info-box">
    <div class="gallery-preview-box">
        <img src="{{ file_path($gallery->preview) }}" alt="">
    </div>
    <div class="gallery-data-box">
        <h2>{{ $gallery->name }}</h2>
        <div class="gallery-description">{{ $gallery->description }}</div>
        <div class="gallery-addtitional-text">{{ $gallery->addtitional_text }}</div>
    </div>
    <div class="dallery-edit-button-box">
        <a href="{{ route('admin.gallery.edit',$gallery) }}" class="default-btn big-square-button edit-button">
            {{__('elfcms::default.edit')}}
        </a>
    </div>
</div>
<div class="gallery-items-box">
    @include('elfcms::admin.gallery.items.content.index')
</div>

@endsection
