<div class="d-flex justify-content-between align-items-end">
    <!-- <div class="col-lg-6 my-2"> -->
    <div class="col-lg-3 order-last order-md-first me-auto">
        <h3 for="" class=" card-title">{{ trans('translation.all-' . $title) }}</h3>
    </div>
    @if (!isset($hide_button))
        @component('components.add-button', ['title' => $title, 'disabled' => $disabled ?? null,'ColDiv' => 'col-lg-6 text-end mx-1', 'data' => $data ?? null])
            @isset($disabled)
                <small class="text-info mx-2">
                    {{$disabled_message ?? ''}}
                </small>
            @endisset
        @endcomponent
        {{$moreButtons??null}}
    @endif
    @if (isset($showSortButton))
        <div class="col-lg-auto text-start">
            <div class="">
                <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="{{ '#sort' . $title }}"
                    ><i
                        class="mdi mdi-sort align-baseline"></i>
                    {{ trans('translation.sort_' . $title) }}
                </button>
            </div>
        </div>
    @endif
</div>
