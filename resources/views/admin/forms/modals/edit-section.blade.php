<!--  modal -->
<div class="modal  fade" id="editSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content border-0">
            <form id="edit-question-form" action="{{route('admin.sections.update')}}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="section_id" id="section_id" value="">
                <div class="modal-header bg-primary p-3">
                    <h6 class="modal-title text-white">{{trans('translation.edit-section')}}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                        <!-- <span aria-hidden="true">×</span> -->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @component('components.inputs.text-input',['columnName'=>'section_name','col'=>'6','margin'=>'mb-3']) @endcomponent
                        @component('components.inputs.number-input',['columnName'=>'section_arrangement','col'=>'6','margin'=>'mb-3']) @endcomponent
                        <input class=" d-none" type="checkbox" role="switch" name="is_visible" checked value="0">
                        <div class="col-6 mb-3">
                            <label for="section_visible" class="col-md-3 form-label">{{ trans('translation.visible') }}</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="form-check form-switch form-switch-md">
                                        <input class="form-check-input" type="checkbox" role="switch" id="section_visible" name="is_visible"  value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-dashed border-top mx-2 p-2"></div>
                <div class="modal-footer">
                    <button class="btn btn-subtle-danger" data-bs-dismiss="modal" type="button"><i class="bi bi-x-lg align-baseline me-1"></i> {{trans('translation.cancel')}}</button>
                    <button class="btn btn-primary" data-bs-dismiss="modal" >{{trans('translation.update')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End  modal -->

@push('after-scripts')
    
    <script>
        $(document).ready(function(){
            $('#editSectionModal').on('show.bs.modal', function(e) {

                // var form_id = $(this).attr('data-form-id');
                // var form_id = e.relatedTarget.id;
                var target = e.relatedTarget;
                var section_id =  target.getAttribute('data-section-id');
                var section_name =  target.getAttribute('data-section-name');
                var section_arrangement =  target.getAttribute('data-section-arrangement');
                var section_visible =  target.getAttribute('data-section-visible');
                $('#section_id').val(section_id);
                $('#input_section_name').val(section_name);
                $('#input_section_arrangement').val(section_arrangement);

                if(section_visible == 1){
                    // $('#section_visible').val(1);
                    $('#section_visible').attr('checked',"checked");
                }else{
                    // $('#section_visible').val(0);
                    $('#section_visible').attr('checked',false);

                }

            });
        })
    </script>
@endpush