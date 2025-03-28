<div class="item-form">
    <h2>{{ $page['title'] }}</h2>
    <form action="{{ route('admin.gallery.items.store',$gallery) }}" method="POST" enctype="multipart/form-data">
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
                    <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
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
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'image', 'code' => 'image']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'preview', 'code' => 'preview']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'thumbnail', 'code' => 'thumbnail']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
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
                            <button type="button" class="button simple-button tag-add-button">Add</button>
                            <div class="tag-prompt-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            @if (empty($isAjax))
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.gallery.items',$gallery) }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            @endif
        </div>
    </form>
</div>
<script>


galleryTagFormInit()

//add editor
////runEditor('#description')
////runEditor('#additional_text')
</script>
