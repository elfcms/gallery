@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ $page['title'] }}</h2>
        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="active">
                        {{ __('elfcms::default.active') }}
                    </label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ true }}" />
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="category_id">{{ __('elfcms::default.category') }}</label>
                    <div class="input-wrapper">
                        <select name="category_id" id="category_id">
                            <option value="0">{{__('gallery::default.no_category')}}</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" @if ($item->active != 1) class="inactive" @endif @if ($item->id == $category_id) selected @endif>{{ $item->name }}@if ($item->active != 1) [{{ __('elfcms::default.inactive') }}] @endif</option>
                        @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}">
                    </div>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="additional_text">{{ __('gallery::default.additional_text') }}</label>
                    <div class="input-wrapper">
                        <textarea name="additional_text" id="additional_text" cols="30" rows="10">{{ old('additional_text') }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="preview">{{ __('elfcms::default.preview') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="" :params="['name' => 'preview', 'code' => 'preview']" :download="true" />
                    </div>
                </div>
                {{-- <div class="input-box colored">
                    <label for="option">{{ __('gallery::default.option') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="option" id="option">
                    </div>
                </div> --}}
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.gallery.index') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
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
