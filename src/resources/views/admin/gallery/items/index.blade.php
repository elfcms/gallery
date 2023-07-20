@extends('gallery::admin.layouts.gallery')
@inject('image', 'Elfcms\Basic\Elf\Image')

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
    @empty($gallery->preview)
        <img src="/vendor/elfcms/gallery/admin/images/empty_270.png" alt="">
    @else
        <img src="{{ $image::cropCache($gallery->preview,270,270) }}" alt="">
    @endempty
    </div>
    <div class="gallery-data-box">
        <h2>{{ $gallery->name }}</h2>
        <div class="gallery-description">{{ $gallery->description }}</div>
        <div class="gallery-addtitional-text">{{ $gallery->addtitional_text }}</div>
    </div>
    <div class="dallery-edit-button-box">
        <a href="{{ route('admin.gallery.edit',$gallery) }}" class="default-btn big-square-button edit-button">
            {{__('basic::elf.edit')}}
        </a>
    </div>
</div>
<div class="gallery-items-box">
    @include('gallery::admin.gallery.items.content.index')
</div>

@endsection
