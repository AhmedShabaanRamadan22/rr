<div class="row">
    <div class="">
        <h4 class="text-danger card-title">
            {{ trans('translation.Remaining-of-attachments')}}
        </h4>
    </div>

    <div class="col-md-6 col-12">
        @foreach ($remaining_attachments as $attach)
            <h6 class="flex-grow-1 mb-0 align-self-center">
                @component('components.data-row', ['id'=>'attachment_label']){{$attach->placeholder}}@endcomponent
                @component('components.data-row', ['id'=>'required'])
                    <i class="{{ $attach->is_required == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger'}}"></i>
                @endcomponent
            </h6>
        @endforeach
    </div>
</div>

<div class="row">
    @forelse ($candidate->attachments_arranged as $attachment)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <h6 class="flex-grow-1 mb-0 align-self-center">
                            {{ ++$loop->index . '- ' . (($attachment_placeholder = $attachment->attachment_label->placeholder) == ' '? trans('translation.attachment') : $attachment_placeholder)}}
                        </h6>
                        <a href="{{$attachment->url}}" download="{{$candidate->name . '_' . $attachment_placeholder}}">
                            <button class="btn btn-outline-primary btn-sm on-default">
                                <i class="mdi mdi-file-download-outline"></i>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{$attachment->url}}" title="{{$attachment->name}}" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- Ratio Video 16:9 -->
    @empty
        <div class="m-1">
            {{trans('translation.no-data')}}
        </div>
    @endforelse
</div>

