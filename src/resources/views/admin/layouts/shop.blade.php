@extends('basic::admin.layouts.basic')

@section('pagecontent')

<div class="big-container">

    {{-- <nav class="pagenav">
        <ul>
            <li>
                <a href="{{ route('admin.simplebox.items') }}" class="button button-left">{{ __('simplebox::elf.items') }}</a>
                <a href="{{ route('admin.simplebox.items.create') }}" class="button button-right">+</a>
            </li>
        </ul>
    </nav> --}}
    @section('shop-content')
    @show

</div>
@endsection
