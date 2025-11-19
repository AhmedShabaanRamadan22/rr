<div class="{{ $div_col ?? '' }} d-flex py-3 border-bottom border-light justify-content-between">
    <label for="{{ $id }}"
        class="fw-semibold {{ $label_col ?? 'col-lg-4 col-5 ' }}">{{ trans('translation.' . $id) }}</label>
    <div id="{{ $id }}" class="{{ $content_col ?? 'col-lg-8 col-7 ' }} text-end text-lg-start">
        {{ !isset($slot) || $slot == '' ? trans('translation.no-data') : $slot }}</div>
</div>
