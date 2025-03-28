@extends('elfcms::admin.layouts.gallery')

@section('gallery-content')

    <div class="item-form">
        <h2>{{ $page['title'] }}</h2>
        <form action="{{ route('admin.gallery.settings.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="image_file_size">{{ __('gallery::default.image_file_size') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="image_file_size" id="image_file_size"
                            value="{{ $settings->image_file_size }}">
                    </div>
                </div>
                <div class="input-box colored" data-change="setting">
                    <label for="is_preview">
                        {{ __('gallery::default.is_preview') }}
                    </label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.switch name="is_preview" id="is_preview"
                            checked="{{ !empty(old('is_preview')) }}" />
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_preview,
                ]) data-setting="is_preview">
                    <label for="preview_file_size">{{ __('gallery::default.preview_file_size') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="preview_file_size" id="preview_file_size"
                            value="{{ $settings->preview_file_size }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_preview,
                ]) data-setting="is_preview">
                    <label for="preview_width">{{ __('gallery::default.preview_width') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="preview_width" id="preview_width"
                            value="{{ $settings->preview_width }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_preview,
                ]) data-setting="is_preview">
                    <label for="preview_heigt">{{ __('gallery::default.preview_heigt') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="preview_heigt" id="preview_heigt"
                            value="{{ $settings->preview_heigt }}">
                    </div>
                </div>
                <div class="input-box colored" data-change="setting">
                    <label for="is_thumbnail">
                        {{ __('gallery::default.is_thumbnail') }}
                    </label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.switch name="is_thumbnail" id="is_thumbnail"
                            checked="{{ !empty(old('is_thumbnail')) }}" />
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_thumbnail,
                ]) data-setting="is_thumbnail">
                    <label for="thumbnail_file_size">{{ __('gallery::default.thumbnail_file_size') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="thumbnail_file_size" id="thumbnail_file_size"
                            value="{{ $settings->thumbnail_file_size }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_thumbnail,
                ]) data-setting="is_thumbnail">
                    <label for="thumbnail_width">{{ __('gallery::default.thumbnail_width') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="thumbnail_width" id="thumbnail_width"
                            value="{{ $settings->thumbnail_width }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_thumbnail,
                ]) data-setting="is_thumbnail">
                    <label for="thumbnail_heigt">{{ __('gallery::default.thumbnail_heigt') }}</label>
                    <div class="input-wrapper">
                        <input type="number" min="1" name="thumbnail_heigt" id="thumbnail_heigt"
                            value="{{ $settings->thumbnail_heigt }}">
                    </div>
                </div>
                <div class="input-box colored" data-change="setting">
                    <label for="is_watermark">
                        {{ __('gallery::default.is_watermark') }}
                    </label>
                    <div class="input-wrapper">
                        <x-elfcms::ui.checkbox.switch name="is_watermark" id="is_watermark"
                            checked="{{ !empty(old('is_watermark')) }}" />
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_watermark,
                ]) data-setting="is_watermark">
                    <label for="watermark">{{ __('gallery::default.watermark') }}</label>
                    <div class="input-wrapper">
                        <x-elf-input-file value="{{ $settings->watermark }}" :params="['name' => 'watermark', 'code' => 'watermark']" accept="jpg,jpeg,png,webp"
                            :download="true" />
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_watermark,
                ]) data-setting="is_watermark">
                    <label>{{ __('gallery::default.watermark_position') }}</label>
                    <div class="input-wrapper">
                        <div class="watermark-position-box">
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="left,top"
                                    @if ($settings->watermark_position == 'left,top') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="center,top"
                                    @if ($settings->watermark_position == 'center,top') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="right,top"
                                    @if ($settings->watermark_position == 'right,top') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="left,center"
                                    @if ($settings->watermark_position == 'left,center') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="center,center"
                                    @if ($settings->watermark_position == 'center,center' || empty($settings->watermark_position)) checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="right,center"
                                    @if ($settings->watermark_position == 'right,center') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="left,bottom"
                                    @if ($settings->watermark_position == 'left,bottom') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="center,bottom"
                                    @if ($settings->watermark_position == 'center,bottom') checked @endif><i></i>
                            </div>
                            <div class="watermark-position">
                                <input type="radio" name="watermark_position" value="right,bottom"
                                    @if ($settings->watermark_position == 'right,bottom') checked @endif><i></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_watermark,
                ]) data-setting="is_watermark">
                    <label for="watermark_size">{{ __('gallery::default.relative_size') }} (%)</label>
                    <div class="input-wrapper">
                        <input type="number" min="0" name="watermark_size" id="watermark_size"
                            value="{{ $settings->watermark_size ?? 50 }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_watermark,
                ]) data-setting="is_watermark">
                    <label for="watermark_indent_h">{{ __('gallery::default.horizontal_indent') }} (px)</label>
                    <div class="input-wrapper">
                        <input type="number" min="0" name="watermark_indent_h" id="watermark_indent_h"
                            value="{{ $settings->watermark_indent_h ?? 0 }}">
                    </div>
                </div>
                <div @class([
                    'input-box',
                    'colored',
                    'collapsed' => !$settings->is_watermark,
                ]) data-setting="is_watermark">
                    <label for="watermark_indent_v">{{ __('gallery::default.vertical_indent') }} (px)</label>
                    <div class="input-wrapper">
                        <input type="number" min="0" name="watermark_indent_v" id="watermark_indent_v"
                            value="{{ $settings->watermark_indent_v ?? 0 }}">
                    </div>
                </div>
                @if (!empty($filesize))
                    <div class="input-box colored">
                        <div class="input-wrapper full-width">
                            <div class="alert alert-notice">
                                {!! __('gallery::default.filesize_attention', ['size' => $filesize]) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <script>
        const checkboxes = document.querySelectorAll('[data-change="setting"] input[type="checkbox"]');
        if (checkboxes) {
            checkboxes.forEach(checkbox => {
                const name = checkbox.name;
                if (name) {
                    checkbox.addEventListener("click", function() {
                        const lines = document.querySelectorAll(`[data-setting="${name}"]`);
                        lines.forEach((line, index) => {
                            if (checkbox.checked) {
                                line.classList.remove('collapsed');
                            } else {
                                line.classList.add('collapsed');
                            }
                        });
                    })
                }
            });
        }
    </script>
@endsection
