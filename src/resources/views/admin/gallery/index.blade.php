@extends('elfcms::admin.layouts.gallery')

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
        <a href="{{route('admin.gallery.create')}}" class="default-btn submit-button create-button">{{__('gallery::default.create_gallery')}}</a>
    </div>
</nav>
<div class="table-search-box">
    <div class="table-search-result-title">
        @if (!empty($search))
            {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.gallery.index') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
        @elseif (!empty($category))
            {!! __('gallery::default.showing_results_for_category',['category'=>'&nbsp;<strong>#'. $category->id .' '. $category->name .'</strong>&nbsp;']) !!} <a href="{{ route('admin.gallery.index') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
        @endif
    </div>
    <form action="{{ route('admin.gallery.index') }}" method="get">
        <div class="input-box">
            <label for="search">
                {{ __('elfcms::default.search') }}
            </label>
            <div class="input-wrapper">
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
            </div>
            <div class="non-text-buttons">
                <button type="submit" class="default-btn search-button"></button>
            </div>
        </div>
    </form>
</div>
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
    <table class="grid-table table-cols-8" style="--first-col:65px; --last-col:160px; --minw:800px">
        <thead>
            <tr>
                <th>
                    ID
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{__('elfcms::default.preview')}}
                </th>
                <th>
                    {{__('elfcms::default.name')}}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                {{-- <th>
                    {{__('elfcms::default.category')}}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'category_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['category_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th> --}}
                <th>
                    {{__('gallery::default.items')}}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'items_count','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['items_count'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.created') }}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.updated') }}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th>
                    {{ __('elfcms::default.active') }}
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['order'=>'active','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['active'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($galleries as $gallery)
            <tr data-id="{{ $gallery->id }}">
                <td>{{ $gallery->id }}</td>
                <td>
                @if (!empty($gallery->preview))
                    <img src="{{ file_path($gallery->preview) }}" alt="{{ $gallery->name }}">
                @endif
                </td>
                <td>
                    <a href="{{ route('admin.gallery.items',$gallery->slug) }}">{{ $gallery->name }}</a>
                </td>
                {{-- <td>
                @empty($gallery->category)
                    {{__('gallery::default.no_category')}}
                @else
                    <a href="{{ route('admin.gallery.index',UrlParams::addArr(['category'=>$gallery->category->id])) }}">{{ $gallery->category->name  }}</a>
                @endempty
                </td> --}}
                <td>{{ $gallery->items_count }}</td>
                <td>{{ $gallery->created_at }}</td>
                <td>{{ $gallery->updated_at }}</td>
                <td>
                @if (!empty($gallery->id))
                    @if ($gallery->active)
                    {{ __('elfcms::default.active') }}
                    @else
                    {{ __('elfcms::default.not_active') }}
                    @endif
                @endif
                </td>
                <td class="button-column non-text-buttons">
                @if (!empty($gallery->id))
                    <a href="{{ route('admin.gallery.items.create',$gallery->slug) }}" class="default-btn submit-button create-button" title="{{ __('gallery::default.create_item') }}"></a>
                    <a href="{{ route('admin.gallery.edit',$gallery->slug) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                    <form action="{{ route('admin.gallery.update',$gallery->slug) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="id" value="{{ $gallery->id }}">
                        <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$gallery->active }}">
                        <input type="hidden" name="notedit" value="1">
                        <button type="submit" @if ($gallery->active == 1) class="default-btn deactivate-button" title="{{__('elfcms::default.deactivate') }}" @else class="default-btn activate-button" title="{{ __('elfcms::default.activate') }}" @endif>

                        </button>
                    </form>
                    <form action="{{ route('admin.gallery.destroy',$gallery->slug) }}" method="POST" data-submit="check">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $gallery->id }}">
                        <input type="hidden" name="name" value="{{ $gallery->name }}">
                        <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                    </form>
                    <div class="contextmenu-content-box">
                        <a href="{{ route('admin.gallery.items.create',$gallery->slug) }}" class="contextmenu-item">{{ __('gallery::default.create_item') }}</a>
                        <a href="{{ route('admin.gallery.edit',$gallery->slug) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                        <form action="{{ route('admin.gallery.update',$gallery->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $gallery->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$gallery->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" class="contextmenu-item">
                            @if ($gallery->active == 1)
                                {{ __('elfcms::default.deactivate') }}
                            @else
                                {{ __('elfcms::default.activate') }}
                            @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.gallery.destroy',$gallery->slug) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $gallery->id }}">
                            <input type="hidden" name="name" value="{{ $gallery->name }}">
                            <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                        </form>
                    </div>
                @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if (empty(count($galleries)))
        <div class="no-results-box">
            {{ __('elfcms::default.nothing_was_found') }}
        </div>
    @endif
</div>
{{$galleries->links('elfcms::admin.layouts.pagination')}}
<script>
const checkForms = document.querySelectorAll('form[data-submit="check"]')
function setConfirmDelete(forms) {
    if (forms) {
        forms.forEach(form => {
            form.addEventListener('submit',function(e){
                e.preventDefault();
                let galleryId = this.querySelector('[name="id"]').value,
                    galleryName = this.querySelector('[name="name"]').value,
                    self = this
                popup({
                    title:'{{ __('elfcms::default.deleting_of_element') }}' + galleryId,
                    content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_gallery') }} "' + galleryName + '" (ID ' + galleryId + ')?</p>',
                    buttons:[
                        {
                            title:'{{ __('elfcms::default.delete') }}',
                            class:'default-btn delete-button',
                            callback: function(){
                                self.submit()
                            }
                        },
                        {
                            title:'{{ __('elfcms::default.cancel') }}',
                            class:'default-btn cancel-button',
                            callback:'close'
                        }
                    ],
                    class:'danger'
                })
            })
        })
    }
}

setConfirmDelete(checkForms)

const tablerow = document.querySelectorAll('.galleries-table tbody tr');
if (tablerow) {
    tablerow.forEach(row => {
        row.addEventListener('contextmenu',function(e){
            e.preventDefault()
            let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
            let forms  = content.querySelectorAll('form[data-submit="check"]')
            setConfirmDelete(forms)
            contextPopup(content,{'left':e.x,'top':e.y})
        })
    })
}

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
