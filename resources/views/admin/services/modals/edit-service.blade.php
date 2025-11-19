<!-- Select2 modal -->
<div class="modal fade modal-lg" id="editService" role="dialog">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content modal-content-demo">
            <form id="form-edit-service" action="{{url('services')}}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary p-3">
                    <h5 class="modal-title text-white" id="SectorLabel">{{ trans('translation.edit-service') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach ($columnInputs as $column => $type)
                            @component('components.inputs.' . $type .'-input',['columnName'=>$column,'col'=>'6','margin'=>'mb-3']) @endcomponent
                        @endforeach
                    </div>
                </div>
                <div class="border-dashed border-top mx-2 p-2"></div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                        <button type="submit" class="btn btn-primary" disabled id="a-services-btn">{{ trans('translation.save-change') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Select2 modal -->
@push('after-scripts')
    <script>
        $(document).ready(function(){
            $('#editService').on('show.bs.modal', function(e) {

                var target = e.relatedTarget;
                $('#input_id').val($(target).attr('data-service-id'));
                $('#input_name_ar').val($(target).attr('data-service-name-ar'));
                $('#input_name_en').val($(target).attr('data-service-name-en'));
                $('#input_price').val($(target).attr('data-service-price'));

            });

            $('#editService .check-empty-input').on('change', function(){
                let flag = true;
                let data_regex = $(this).attr("data-regex");
                let column_name = $(this).attr("name");
                if (typeof data_regex !== 'undefined' && data_regex !== false) {
                    var regex = new RegExp(data_regex);
                    if (!regex.test($(this).val())) {
                        $('#error-' + column_name).removeClass('d-none')
                        $('#input_' + column_name).addClass('border-danger')
                        return
                    }
                    $('#error-' + column_name).addClass('d-none')
                    $('#input_' + column_name).removeClass('border-danger')
                }
                $('#editService .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#a-services-btn').prop('disabled', !flag)
            })

            $('form').on('submit', function(e){
                let sendBtn = $('#a-services-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
            })
        });
    </script>
@endpush