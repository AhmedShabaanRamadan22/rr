<div class="row">
    <div class="">
        <h4 class="text-danger card-title">
            {{ trans('translation.Remaining-of-attachments')}}
        </h4>
    </div>

    @foreach ($remaining_attachments as $attach)
    <div class="col-md-6 col-12">
            @component('components.data-row', ['id'=>'attachment_label'])
                {{$attach->placeholder}} 
                <strong>[{{$attach->required_text}}]</strong>
            @endcomponent
    </div>
        @endforeach
</div>

<div class="row">
    @forelse ($facility->attachments_arranged as $attachment)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <h6 class="flex-grow-1 mb-0 align-self-center">
                            {{ ++$loop->index . '- ' . (($attachment_placeholder = $attachment->attachment_label->placeholder) == ' '? trans('translation.attachment') : $attachment_placeholder)}}
                        </h6>
                        <a href="{{$attachment->url}}" download="{{$facility->name . '_' . $attachment_placeholder}}">
                            <button class="btn btn-outline-primary btn-sm on-default">
                                <i class="mdi mdi-file-download-outline"></i>
                            </button>
                        </a>
                        {{-- <button class="btn btn-outline-secondary btn-sm mx-2 on-default  edit-facility-attachment-button" > data-bs-target="#editQuestion" data-bs-toggle="modal" data-original-title="Edit" data-question-id="' . $question->id . '"> --}}
                            {{-- <i class="mdi mdi-clipboard-edit-outline"></i>
                            </button>

                            <button class="btn btn-outline-danger btn-sm on-default delete-facility-attachment-button" data-question-id="' . $question->id . '">
                                <i class="mdi mdi-delete"></i>
                            </button> --}}
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

