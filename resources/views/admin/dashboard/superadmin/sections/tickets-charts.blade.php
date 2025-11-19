<div class="col-xl-3 col-lg-6">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h6 class="card-title text-primary mb-0 flex-grow-1">{{ trans('translation.tickets detail') }}</h6>
        </div>
        <div class="card-body">
            <div id="multiRadialChart" data-colors='{{ json_encode($dangerColor) }}' dir="ltr"></div>
        </div>
    </div>
</div>