@extends('elfcms::admin.layouts.gallery')

@section('gallery-content')

    @if (Session::has('gallerysuccess'))
        <div class="alert alert-success">{{ Session::get('gallerysuccess') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="item-form">
        <h3>{{ $page['title'] }}</h3>
        <form action="{{ route('admin.gallery.update',$gallery->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" style="blue" :checked="$gallery->active" />
                </div>
                {{-- <div class="input-box colored">
                    <label for="category_id">{{ __('elfcms::default.category') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id" id="category_id">
                            <option value="0">{{__('gallery::default.no_category')}}</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @if ($item->active != 1) class="inactive" @endif @if ($item->id == $gallery->category_id) selected @endif>{{ $item->name }}@if ($item->active != 1) [{{ __('elfcms::default.inactive') }}] @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{$gallery->name}}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" value="{{$gallery->slug}}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{$gallery->description}}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="additional_text">{{ __('gallery::default.additional_text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="additional_text" id="additional_text" cols="30" rows="10">{{$gallery->additional_text}}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="preview">{{ __('elfcms::default.preview') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image code="preview" value="{{$gallery->preview}}" />
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="option">{{ __('gallery::default.option') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="option" id="option" value="{{$gallery->option}}">
                    </div>
                </div> --}}
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    const previewInput = document.querySelector('#preview')
    if (previewInput) {
        inputFileImg(previewInput)
    }
    autoSlug('.autoslug')

    //add editor
    runEditor('#description')
    runEditor('#additional_text')
    </script>

@endsection
