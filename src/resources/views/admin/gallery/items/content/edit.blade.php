<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
    <form action="{{ route('admin.gallery.items.update',['gallery'=>$gallery,'galleryItem'=>$item]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label for="active">
                    {{ __('elfcms::default.active') }}
                </label>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $item->active == 1 }}" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" value="{{ $item->name }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="slug">{{ __('elfcms::default.slug') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="slug" id="slug" value="{{ $item->slug }}">
                </div>
                <div class="input-wrapper">
                    <x-elfcms::ui.checkbox.autoslug textid="name" slugid="slug" checked="true" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <textarea name="description" id="description">{{ $item->description }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="additional_text">{{ __('gallery::default.additional_text') }}</label>
                <div class="input-wrapper">
                    <textarea name="additional_text" id="additional_text">{{ $item->additional_text }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="{{ $item->image }}" :params="['name' => 'image', 'code' => 'image']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="{{ $item->preview }}" :params="['name' => 'preview', 'code' => 'preview']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="{{ $item->thumbnail }}" :params="['name' => 'thumbnail', 'code' => 'thumbnail']" :download="true" accept=".jpg,.jpeg,.png,.webp" />
                </div>
            </div>
            @endif

            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{ $item->position }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link">{{ __('elfcms::default.link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link" id="link" value="{{ $item->link }}">
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="option">{{ __('gallery::default.option') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="option" id="option" value="{{ $item->option }}">
                </div>
            </div> --}}
            <div class="input-box colored">
                <label for="tags">{{ __('elfcms::default.tags') }}</label>
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
runEditor('#description')
runEditor('#additional_text')
</script>
