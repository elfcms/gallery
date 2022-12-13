@extends('gallery::admin.layouts.gallery')

@section('gallery-content')

    @if (Session::has('tagedited'))
        <div class="alert alert-success">{{ Session::get('tagedited') }}</div>
    @endif
    @if (Session::has('tagcreated'))
        <div class="alert alert-success">{{ Session::get('tagcreated') }}</div>
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
        <h3>{{ __('basic::elf.edit_tag') }}{{ $tag->id }}</h3>
        <form action="{{ route('admin.gallery.tags.update',$tag) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="name">{{ __('basic::elf.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $tag->name }}">
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('basic::elf.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
    tagInput('#name')
    </script>

@endsection
