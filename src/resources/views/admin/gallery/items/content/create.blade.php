<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
    <form action="{{ route('admin.gallery.items.store',$gallery) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="colored-rows-box">
            <div class="input-box colored">
                <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" checked style="blue" />
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" autocomplete="off">
                </div>
            </div>
            <div class="input-box colored">
                <label for="slug">{{ __('elfcms::default.slug') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="slug" id="slug" autocomplete="off">
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
                    <textarea name="description" id="description"></textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="additional_text">{{ __('gallery::default.additional_text') }}</label>
                <div class="input-wrapper">
                    <textarea name="additional_text" id="additional_text"></textarea>
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="image_path" id="image_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="image" id="image">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="preview_path" id="preview_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="preview" id="preview">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="thumbnail_path" id="thumbnail_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail">
                    </div>
                </div>
            </div> --}}
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="image" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="preview" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="thumbnail" />
                </div>
            </div>
            @endif
            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{$position}}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link">{{ __('elfcms::default.link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link" id="link">
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="option">{{ __('gallery::default.option') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="option" id="option">
                </div>
            </div> --}}
            <div class="input-box colored">
                <label for="tags">{{ __('elfcms::default.tags') }}</label>
                <div class="input-wrapper">
                    <div class="tag-form-wrapper">
                        <div class="tag-list-box"></div>
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
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
        </div>
    </form>
</div>
<script>
const previewInput = document.querySelector('#preview')
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
