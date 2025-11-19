
<div class="row">
    @forelse($order->organization_service->questions as $key => $question)
    <div class="col-6">
        <x-row-info id="question-{{$question->id}}" label="{{$question->content}}">{{($question->user_answer($order)->value??'('.trans('translation.have-no-answer').')')}}</x-row-info>
    </div>
    @empty
    <div class="col-md-12 col-sm-12 mb-4 text-center">
        @lang('translation.no-data')
    </div>
    @endforelse
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endpush