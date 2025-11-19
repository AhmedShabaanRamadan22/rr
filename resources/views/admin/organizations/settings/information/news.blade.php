@component('components.nav-pills.tab-pane', ['id' => $column['name'], 'padding' => 'p-1'])
    <div class="row mt-2">
        <div class="col-lg-12">
            @component('components.section-header', ['title' => 'news'])@endcomponent
        </div>
        <!--end row-->
    </div>
    <div class="mt-2">
        <div class="row">
            <div data-simplebar style="height: 464px;" class="mt-2">
                <ul class="list list-group list-group-flush mb-0">
                    @forelse ($organization->organization_news as $index => $new)
                        <form action="{{ route('organization-news.destroy', $new->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <li class="list-group-item border mt-2 rounded-2 newsCard" data-id="01">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div
                                            class="align-items-lg-center avatar-xs bg-secondary d-flex justify-content-center rounded-circle">
                                            <i
                                                class="align-middle mdi mdi-18px mdi-lg mdi-newspaper-variant text-light"></i>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="contact-name fs-sm mb-1">
                                            {{ trans('translation.one-new') . ' ' . ++$index }}
                                            <span class="badge bg-primary-subtle mx-4 text-black-50">
                                                <small>
                                                    {{ $new->created_at->diffForHumans(['locale' => 'ar']) }}
                                                </small>
                                            </span>
                                        </h5>
                                        <p class="contact-born text-muted mb-0">{{ $new->new }}</p>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <div class="fs-2xs text-muted">
                                            <button type="button" class="btn btn-danger btn-sm deleteNew" data-news-id={{$new->id}}>
                                                <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </form>
                    @empty
                        <p class="text-center">{{ trans('translation.not_found') }}</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endcomponent