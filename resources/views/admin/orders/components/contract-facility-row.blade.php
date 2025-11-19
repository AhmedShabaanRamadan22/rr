<!-- User Card -->
<div class="col-md-6 col-sm-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>
                {{trans('translation.question')}}
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($order->organization_service->questions as $key => $question)
                <div class="col-md-6 col-sm-12 mb-4">
                    <strong>{{$question->content}}</strong>
                    <p>{{($question->user_answer($order)->value??'('.trans('translation.have-no-answer').')')}}</p>
                </div>
                @empty
                <div class="col-md-12 col-sm-12 mb-4 text-center">
                    @lang('translation.no_data')
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<!-- Order Card -->
<div class="col-md-6 col-sm-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>
                {{trans('translation.contract')}}
            </h3>

        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($order->contracts as $contract)
                <div class="col-md-4 col-lg-12">

                </div>
                @empty
                    <div class="col-md-12 col-lg-12 text-center">
                        @lang('translation.no-contract')
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>