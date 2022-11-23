@extends('basic::admin.layouts.basic')

@section('pagecontent')

<div class="big-container">
    <div class="bigtile-box">
        @foreach ($categories as $category)
        {{$category->name}}
        <br>

            @foreach ($category->galleries as $gallery)
            &nbsp;&nbsp;&nbsp;&nbsp;{{$gallery->name}}
            <br>
                @foreach ($gallery->items as $item)
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$item->name}}
                <br>
                @endforeach
            @endforeach
        @endforeach
    </div>
</div>

@endsection
