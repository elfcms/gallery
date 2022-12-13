<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
    <form action="{{ route('admin.gallery.items.update',['gallery'=>$gallery,'galleryItem'=>$item]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="colored-rows-box">
            <div class="input-box colored">
                <div class="checkbox-wrapper">
                    <div class="checkbox-inner">
                        <input
                            type="checkbox"
                            name="active"
                            id="active"
                            @if ($item->active == 1)
                            checked
                            @endif
                        >
                        <i></i>
                        <label for="active">
                            {{ __('basic::elf.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('basic::elf.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" value="{{ $item->name }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="slug">{{ __('basic::elf.slug') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="slug" id="slug" value="{{ $item->slug }}">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="desctiption">{{ __('basic::elf.description') }}</label>
                <div class="input-wrapper">
                    <textarea name="description" id="description">{{ $item->description }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="additional_text">{{ __('gallery::elf.additional_text') }}</label>
                <div class="input-wrapper">
                    <textarea name="additional_text" id="additional_text">{{ $item->additional_text }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="image">{{ __('basic::elf.image') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="image_path" id="image_path" value="{{ $item->image }}">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                        @if (!empty($item->image))
                            <img src="{{ asset($item->image) }}" alt="">
                        @else
                            <img src="{{ asset('/vendor/elfcms/gallery/admin/images/icons/upload.png') }}" alt="Upload file">
                        @endif
                        </div>
                        <div class="image-button-text">
                            @if (!empty($item->image))
                            {{ __('basic::elf.change_file') }}
                        @else
                            {{ __('basic::elf.choose_file') }}
                        @endif
                        </div>
                        <input type="file" name="image" id="image">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="preview">{{ __('basic::elf.preview') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="preview_path" id="preview_path" value="{{ $item->preview }}">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                        @if (!empty($item->preview))
                            <img src="{{ asset($item->preview) }}" alt="">
                        @else
                            <img src="{{ asset('/vendor/elfcms/gallery/admin/images/icons/upload.png') }}" alt="Upload file">
                        @endif
                        </div>
                        <div class="image-button-text">
                            @if (!empty($item->preview))
                            {{ __('basic::elf.change_file') }}
                        @else
                            {{ __('basic::elf.choose_file') }}
                        @endif
                        </div>
                        <input type="file" name="preview" id="preview">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="thumbnail">{{ __('basic::elf.thumbnail') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="thumbnail_path" id="thumbnail_path" value="{{ $item->thumbnail }}">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                        @if (!empty($item->thumbnail))
                            <img src="{{ asset($item->thumbnail) }}" alt="">
                        @else
                            <img src="{{ asset('/vendor/elfcms/gallery/admin/images/icons/upload.png') }}" alt="Upload file">
                        @endif
                        </div>
                        <div class="image-button-text">
                        @if (!empty($item->thumbnail))
                            {{ __('basic::elf.change_file') }}
                        @else
                            {{ __('basic::elf.choose_file') }}
                        @endif
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="position">{{ __('basic::elf.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{ $item->position }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link">{{ __('basic::elf.link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link" id="link" value="{{ $item->link }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="option">{{ __('gallery::elf.option') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="option" id="option" value="{{ $item->option }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="tags">{{ __('basic::elf.tags') }}</label>
                <div class="input-wrapper">
                    <div class="tag-form-wrapper">
                        <div class="tag-list-box">
                            @foreach ($item->tags as $tag)
                            <div class="tag-item-box" data-id="{{ $tag->id }}">
                                <span class="tag-item-name">{{ $tag->name }}</span>
                                <span class="tag-item-remove" onclick="removeTagFromList(this)">&#215;</span>
                                <input type="hidden" name="tags[]" value="{{ $tag->id }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="tag-input-box">
                            <input type="text" class="tag-input" autocomplete="off">
                            <button type="button" class="default-btn tag-add-button">Add</button>
                            <div class="tag-prompt-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('basic::elf.submit') }}</button>
        </div>
    </form>
</div>
<script>
const previewInput = document.querySelector('#preview')
console.log(previewInput)
if (previewInput) {
    inputFileImg(previewInput)
}
const imageInput = document.querySelector('#image')
if (imageInput) {
    inputFileImg(imageInput)
}
const thumbnailInput = document.querySelector('#thumbnail')
if (thumbnailInput) {
    inputFileImg(thumbnailInput)
}
autoSlug('.autoslug')

galleryTagFormInit()

//add editor
runEditor('#description')
runEditor('#additional_text')
</script>
