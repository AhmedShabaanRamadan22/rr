<div class="{{ $col ?? 'col-lg-2' }}">
    <div class="card shadow-none {{ $end ?? 'border-end-md' }} {{ $bottom ?? 'border-bottom' }} rounded-0 mb-0">
        <div class="card-body d-flex align-items-center">
            <div class="avatar-sm me-3">
                <span class="avatar-title {{ $bgcolor }} {{ $iccolor }} rounded-circle fs-3">
                    <i class="{{ $icon }}"></i>
                </span>
            </div>
            <div>
                <p class="text-uppercase fw-medium {{ $iccolor }} text-truncate fs-sm">
                    {{ trans($label) }}
                </p>
                <h4 class="fw-semibold mb-0">
                    <span id="{{$target}}" class="" data-target="">
                        @if(isset($data))
                            {{$data}}
                        @else
                            <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        @endif
                    </span>
                </h4>
            </div>
                        @if (isset($description))
                            <div class="position-absolute top-0 end-0 custom-tooltip " data-bs-toggle="tooltip" data-bs-placement="top" title="{{ trans('translation.' . $description) }}">
                                <div class="rounded-circle">
                                    <i class="ph-info fs-5"></i>
                                </div>
                            </div>
                       @endif
        </div>
    </div>
</div>
@push('styles')
<style>
    .custom-tooltip {
        width: 2rem;
        margin-top: 20px;
    }
</style>
@endpush
