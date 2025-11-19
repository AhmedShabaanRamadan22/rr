<div class="row ">
    <div class="acitivity-timeline acitivity-main">
        @forelse ($audits->whereNotIn('event',['downloaded']) as $audit)
        <div class="acitivity-item d-flex">
            <div class="flex-shrink-0">
                <img src="{{ auditer_profile($audit) }}" alt=""
                    class="avatar-xs rounded-circle acitivity-avatar bg-primary">
            </div>
            {{-- {{dd($audit)}} --}}
            <div class="flex-grow-1 ms-3 pb-4">
                <h6 class="mb-1 lh-base">{{auditer_name($audit)}}</h6>
                <pre class="mb-1 lh-base">{!!audit_value_changes($audit)!!}</pre>
                <small class="text-muted">{{$audit->updated_at->diffForHumans()}}</small>
            </div>
        </div>

        @empty
        <div class="text-center">{{trans('translation.no-audits')}}</div>
        @endforelse
    </div>
</div>