@component('components.nav-pills.tab-pane', ['id' => $key, 'title' => $key])
<div class="row">
    @forelse ($support->support_attachments as $attachment)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <h6 class="flex-grow-1 mb-0 align-self-center">
                            {{ trans('translation.attachment') . ' ' . ++$loop->index }}
                        </h6>
                        <div class="space-x-3">
                            <a href="{{$attachment->url}}" target="_blank">
                                <button class="btn btn-outline-secondary btn-sm on-default">
                                    <i class="mdi mdi-eye-outline"></i>
                                </button>
                            </a>
                            <a href="{{$attachment->url}}" download="{{$support->order_sector->order->facility->name . '_' . $attachment_placeholder = $attachment->attachment_label->placeholder}}">
                                <button class="btn btn-outline-primary btn-sm on-default">
                                    <i class="mdi mdi-file-download-outline"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{$attachment->url}}" title="{{$attachment->name}}" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <div class="m-1">
        {{trans('translation.no-data')}}
    </div>
    @endforelse
</div>

    {{-- <div id="carouselExampleIndicators" class="carousel slide carousel-dark" data-bs-ride="carousel">
        <div class="carousel-inner" role="listbox">
            @foreach ($support->support_attachments as $attachment)
                <div class="carousel-item {{ $loop->index == 0 ? 'active' : '' }} h-100">
                    <img class="d-block img-fluid mx-auto object-fit-contain rounded"
                        src="{{ $attachment->url }}" style="">
                    <div class="carousel-caption text-white bg-light bg-opacity-75"
                        style="left: 0%; right: 0%; padding-top: 0.5rem; padding-bottom: 0.5rem; bottom: 0">
                        <a href="{{ $attachment->url }}"
                            download="{{ $support->id . '_' . $attachment->attachment_label->placeholder }}"
                            class="btn btn-primary">{{ trans('translation.download') }}</a>
                        <a href="{{ $attachment->url }}" target="_blank"
                            class="btn btn-secondary">{{ trans('translation.view') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
            data-bs-slide="next">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">{{ trans('translation.next') }}</span>
        </a>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
            data-bs-slide="prev">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">{{ trans('translation.previous') }}</span>
        </a>
    </div> --}}
@endcomponent