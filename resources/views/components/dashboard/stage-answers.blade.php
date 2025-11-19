<div>
    <div class="text-primary fw-bolder py-3">{{$stage}}</div>
    @forelse($answers as $answer)
        <div class="border-bottom border-light py-2">
            <span class="fw-bold pe-3">{{$answer['question']}}</span>
            <span>{!! $answer['answer'] !!}</span>
        </div>
    @empty
    <div class="text-center"><p>{{ trans('translation.no-data') }}</p></div>
    @endforelse
</div>
