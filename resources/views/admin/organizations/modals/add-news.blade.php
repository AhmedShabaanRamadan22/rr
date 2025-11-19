{{-- <div class="modal fade" id="addnews" tabindex="-1" aria-labelledby="addNewsLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content  border-0">
            <div class="modal-header  bg-primary p-3">
                <h5 class="modal-title" id="addNewsLabel">{{ trans('translation.add-new-news') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal" action="{{ route('organization-news.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="organization_id" id="hidden_organization_id_news" value="{{$organization->id}}">
                    <div class="mb-3">
                        <label for="new" class="form-label">{{ trans('translation.one-new') }} <span
                            class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="new" id="new"
                            placeholder="{{ trans('translation.one-new') }}">
                    </div>
                    
                    <!--end row-->
                </div>
                <div class="border-dashed border-top mx-2 p-2"></div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i
                                class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                        <button type="submit" class="btn btn-primary"
                            id="add-btn">{{ trans('translation.add') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@component('modals.add-modal-template',['modalName'=>'news', 'modalRoute'=>'organization-news'])
    <input type="hidden" name="organization_id" id="hidden_organization_id_news" value="{{$organization->id}}">
    @component('components.inputs.text-input',['columnName'=>'the_new','col'=>'12','margin'=>'mb-3']) @endcomponent
@endcomponent