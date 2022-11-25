@extends('gallery::admin.layouts.gallery')

@section('gallery-content')

{{-- <div class="big-container">
    <div class="bigtile-box">
        @foreach ($categories as $category)
        {{$category->name}}
        <br>

            @foreach ($category->galleries as $gallery)
            &nbsp;&nbsp;&nbsp;&nbsp;{{$gallery->name}}
            <br>
                {{-- @foreach ($gallery->items as $item)
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$item->name}}
                <br>
                @endforeach --}
            @endforeach
        @endforeach
    </div>
</div> --}}
<nav class="pagenav">
    <div class="abstract-nav-line">
        <a href="{{route('admin.gallery.categories.create')}}" class="default-btn submit-button create-button">{{__('basic::elf.create_category')}}</a>
    </div>
</nav>
{{-- <div class="table-search-box">
    <div class="table-search-result-title">
        @if (!empty($search))
            {{ __('basic::elf.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.gallery.index') }}" title="{{ __('basic::elf.reset_search') }}">&#215;</a>
        @endif
    </div>
    <form action="{{ route('admin.gallery.index') }}" method="get">
        <div class="input-box">
            <label for="search">
                {{ __('basic::elf.search') }}
            </label>
            <div class="input-wrapper">
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
            </div>
            <div class="non-text-buttons">
                <button type="submit" class="default-btn search-button"></button>
            </div>
        </div>
    </form>
</div> --}}
@if (Session::has('categorysuccess'))
<div class="alert alert-alternate">{{ Session::get('categorysuccess') }}</div>
@endif
@if (Session::has('gallerysuccess'))
<div class="alert alert-alternate">{{ Session::get('gallerysuccess') }}</div>
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
<div class="widetable-wrapper">
    <table class="grid-table galleries-by-category">
        <thead>
            <tr>
                <th>
                    ID
                    {{-- <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                </th>
                <th>
                    {{__('blog::elf.preview')}}
                </th>
                <th>
                    {{__('basic::elf.name')}}
                    {{-- <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                </th>
                <th>
                    {{ __('basic::elf.created') }}
                    {{-- <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                </th>
                <th>
                    {{ __('basic::elf.updated') }}
                    {{-- <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                </th>
                <th>
                    {{ __('basic::elf.active') }}
                    {{-- <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'active','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['active'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a> --}}
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($categories as $category)
            <tr data-id="{{ $category->id }}">
                <td>{{ $category->id }}</td>
                <td>
                @if (!empty($category->preview))
                    <img src="{{ asset($category->preview) }}" alt="{{ $category->name }}">
                @endif
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->created_at }}</td>
                <td>{{ $category->updated_at }}</td>
                <td>
                @if (!empty($category->id))
                    @if ($category->active)
                    {{ __('basic::elf.active') }}
                    @else
                    {{ __('basic::elf.not_active') }}
                    @endif
                @endif
                </td>
                <td class="button-column non-text-buttons">
                @if (!empty($category->id))
                    <form action="{{ route('admin.gallery.create') }}" method="GET">
                        <input type="hidden" name="category_id" value="{{$category->id}}">
                        <button type="submit" class="default-btn submit-button create-button" title="{{__('gallery::elf.create_gallery')}}"></button>
                    </form>
                    <a href="{{ route('admin.gallery.edit',$category->id) }}" class="default-btn edit-button" title="{{ __('basic::elf.edit') }}"></a>
                    <form action="{{ route('admin.gallery.update',$category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                        <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                        <input type="hidden" name="notedit" value="1">
                        <button type="submit" @if ($category->active == 1) class="default-btn deactivate-button" title="{{__('basic::elf.deactivate') }}" @else class="default-btn activate-button" title="{{ __('basic::elf.activate') }}" @endif>

                        </button>
                    </form>
                    <form action="{{ route('admin.gallery.destroy',$category->id) }}" method="POST" data-submit="check">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $category->id }}">
                        <input type="hidden" name="email" value="{{ $category->email }}">
                        <button type="submit" class="default-btn delete-button" title="{{ __('basic::elf.delete') }}"></button>
                    </form>
                    <div class="contextmenu-content-box">
                        <form action="{{ route('admin.gallery.create') }}" method="GET">
                            <input type="hidden" name="category_id" value="{{$category->id}}">
                            <button type="submit" class="contextmenu-item" title="{{__('gallery::elf.create_gallery')}}"></button>
                        </form>
                        <a href="{{ route('admin.gallery.edit',$category->id) }}" class="contextmenu-item">{{ __('basic::elf.edit') }}</a>
                        <form action="{{ route('admin.gallery.update',$category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" class="contextmenu-item">
                            @if ($category->active == 1)
                                {{ __('basic::elf.deactivate') }}
                            @else
                                {{ __('basic::elf.activate') }}
                            @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.gallery.destroy',$category->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <input type="hidden" name="email" value="{{ $category->email }}">
                            <button type="submit" class="contextmenu-item">{{ __('basic::elf.delete') }}</button>
                        </form>
                    </div>
                @endif
                </td>
            </tr>
            <tr class="full-width">
                <td colspan="7">
                    <h6 @class(['notempty' => $category->galleries->count()>0]) data-hide="({{__('gallery::elf.hide')}})" data-show="({{__('gallery::elf.show')}})">{{__('gallery::elf.galleries')}}: {{$category->galleries->count()}} </h6>
                    <div class="table-collapse">
                    @if(!empty($category->galleries))
                        <table class="grid-table galleries-by-category">
                            <thead>
                                <tr>
                                    <th>
                                        ID
                                    </th>
                                    <th>
                                        {{__('blog::elf.preview')}}
                                    </th>
                                    <th>
                                        {{__('basic::elf.name')}}
                                    </th>
                                    <th>
                                        {{ __('basic::elf.created') }}
                                    </th>
                                    <th>
                                        {{ __('basic::elf.updated') }}
                                    </th>
                                    <th>
                                        {{ __('basic::elf.active') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($category->galleries as $gallery)
                                <tr data-id="{{ $gallery->id }}">
                                    <td>{{ $gallery->id }}</td>
                                    <td><img src="{{ asset($gallery->preview) }}" alt="{{ $gallery->name }}"></td>
                                    <td>{{ $gallery->name }}</td>
                                    <td>{{ $gallery->created_at }}</td>
                                    <td>{{ $gallery->updated_at }}</td>
                                    <td>
                                    @if ($gallery->active)
                                        {{ __('basic::elf.active') }}
                                    @else
                                        {{ __('basic::elf.not_active') }}
                                    @endif
                                    </td>
                                    <td class="button-column non-text-buttons">
                                        <a href="{{ route('admin.gallery.edit',$gallery->id) }}" class="default-btn edit-button" title="{{ __('basic::elf.edit') }}"></a>
                                        <form action="{{ route('admin.gallery.update',$gallery->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" id="id" value="{{ $gallery->id }}">
                                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$gallery->active }}">
                                            <input type="hidden" name="notedit" value="1">
                                            <button type="submit" @if ($gallery->active == 1) class="default-btn deactivate-button" title="{{__('basic::elf.deactivate') }}" @else class="default-btn activate-button" title="{{ __('basic::elf.activate') }}" @endif>

                                            </button>
                                        </form>
                                        <form action="{{ route('admin.gallery.destroy',$gallery->id) }}" method="POST" data-submit="check">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $gallery->id }}">
                                            <input type="hidden" name="email" value="{{ $gallery->email }}">
                                            <button type="submit" class="default-btn delete-button" title="{{ __('basic::elf.delete') }}"></button>
                                        </form>
                                        <div class="contextmenu-content-box">
                                            <a href="{{ route('admin.gallery.edit',$gallery->id) }}" class="contextmenu-item">{{ __('basic::elf.edit') }}</a>
                                            <form action="{{ route('admin.gallery.update',$gallery->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id" id="id" value="{{ $gallery->id }}">
                                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$gallery->active }}">
                                                <input type="hidden" name="notedit" value="1">
                                                <button type="submit" class="contextmenu-item">
                                                @if ($gallery->active == 1)
                                                    {{ __('basic::elf.deactivate') }}
                                                @else
                                                    {{ __('basic::elf.activate') }}
                                                @endif
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.gallery.destroy',$gallery->id) }}" method="POST" data-submit="check">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $gallery->id }}">
                                                <input type="hidden" name="email" value="{{ $gallery->email }}">
                                                <button type="submit" class="contextmenu-item">{{ __('basic::elf.delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if (empty(count($categories)))
        <div class="no-results-box">
            {{ __('basic::elf.nothing_was_found') }}
        </div>
    @endif
</div>
{{-- {{$categories->links('basic::admin.layouts.pagination')}} --}}
<script>
const tableExpander = document.querySelectorAll('h6.notempty');
if (tableExpander) {
    tableExpander.forEach(element => {
        element.addEventListener('click',function(e){
            e.preventDefault();
            let tableBox = element.parentNode.querySelector('.table-collapse')
            if (tableBox) {
                if (tableBox.classList.contains('expanded')) {
                    tableBox.classList.remove('expanded');
                    element.classList.remove('expanded');
                }
                else {
                    tableBox.classList.add('expanded');
                    element.classList.add('expanded');
                }
            }
        });
    });
}
</script>
@endsection
