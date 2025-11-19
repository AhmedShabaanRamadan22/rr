
<div class="mb-3 row">
    <div class="col-3 col-lg-3 align-self-lg-center align-self-center">
        <label for="{{ $id }}" class="h6">{{ trans('translation.' . $title) }}:</label>
    </div>
    <div class="col-9 col-lg-9 align-self-lg-center align-self-center">
        {{ $slot }}
    </div>
</div>
