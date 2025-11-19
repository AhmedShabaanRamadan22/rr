<div class="modal fade" id="{{$id . '-note-history'}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{trans('translation.note-history')}}</h4>
                <span type="button" class="text-primary h3" data-bs-dismiss="modal"><i class="mdi mdi-close"></i></span>
            </div>
            <div class="modal-body">
                <div class="acitivity-timeline acitivity-main">
                @forelse ($model->notes as $note)
                    <div class="acitivity-item d-flex">
                        <div class="flex-shrink-0">
                            <img src="{{ $note->user->profile_photo }}" alt="" class="avatar-xs rounded-circle acitivity-avatar bg-primary">
                        </div>
                        {{-- {{dd($note)}} --}}
                        <div class="flex-grow-1 ms-3 pb-4 text-start">
                            <h6 class="mb-1 lh-base">{{$note->user_name}}</h6>
                            <p class="text-muted mb-2">{{$note->content}}</p>
                            <small class="text-muted">{{$note->updated_at->diffForHumans()}}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">{{trans('translation.no-notes')}}</div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
