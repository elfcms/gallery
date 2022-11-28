@extends('gallery::admin.layouts.gallery')

@section('gallery-content')

    @if (Session::has('postedited'))
        <div class="alert alert-success">{{ Session::get('postedited') }}</div>
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

    @include('gallery::admin.gallery.items.content.create')

@endsection
