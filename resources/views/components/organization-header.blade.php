<div class="row align-items-center gy-3">
    <div class="col-lg-3 order-last order-md-first me-auto">
        <label for="" class="form-label">{{ trans('translation.' . $title) }}</label>
    </div>
    @if (!isset($hide_button))
        @component('components.add-button', ['title' => $title, 'disabled' => $disabled ?? null, 'data' => $data ?? null] ){{$slot}}@endcomponent
    @endif
    @if (isset($showSortButton))
        <div class="col-lg-auto text-start">
            <div class=" gap-2">
                <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="{{ '#sort' . $title }}"
                    ><i
                        class="mdi mdi-sort align-baseline me-1"></i>
                    {{ trans('translation.sort_' . $title) }}
                </button>
            </div>
        </div>
    @endif
</div>
