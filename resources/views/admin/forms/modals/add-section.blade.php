{{-- <!--  modal -->
<div class="modal  fade" id="addSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered ">
        <div class="modal-content ">
            <form id="edit-question-form" action="{{route('admin.sections.store')}}" method="post" onsubmit="formSubmitted()">
                @csrf
                <input type="hidden" name="form_id" id="addSectionFormId" value="">
                <div class="modal-header">
                    <h6 class="modal-title">{{trans('translation.add-new-section')}}</h6>
                    <button class="btn-close ml-0" data-bs-dismiss="modal" aria-label="Close" type="button">
                        <!-- <span aria-hidden="true">×</span> -->
                    </button>
                </div>
                <div class="modal-body">

                    <div class=" row mb-4">
                        <label for="content" class="col-md-3 form-label">{{ trans('translation.name') }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('translation.enter-name') }}" required>
                            <div class="d-none text-danger">{{ trans('translation.this-field-is-required') }}</div>
                        </div>
                    </div>
                    <div class=" row mb-4">
                        <label for="is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="form-check form-switch form-switch-md">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" checked value="1">
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-success" data-bs-dismiss="modal" >{{trans('translation.save')}}</button>
                    <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">{{trans('translation.cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End  modal -->
--}}

@component('modals.add-modal-template',['modalName'=>'sections', 'modalRoute'=>'admin.sections'])
    <input type="hidden" name="form_id" id="addSectionFormId" value="">
    @component('components.inputs.text-input',['columnName'=>'name','col'=>'6','margin'=>'mb-3']) @endcomponent
    @component('components.inputs.number-input',['columnName'=>'arrangement','col'=>'6','margin'=>'mb-3']) @endcomponent
    <div class="col-md-6 mb-3">
        <label for="is_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
        <div class="col-md-9">
            <div class="form-group">
                <div class="form-check form-switch form-switch-md">
                    <input type="hidden" name="is_visible" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" checked value="1">
                </div>
            </div>
        </div>
    </div>
@endcomponent

@push('after-scripts')
    <script>
        $(document).ready(function(){
            $('#addsections').on('show.bs.modal', function(e) {

                // var form_id = $(this).attr('data-form-id');
                var form_id = e.relatedTarget.id;
                $('#addSectionFormId').val(form_id)

            });
        })
    </script>
@endpush 
