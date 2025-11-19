@component('modals.modal-template',[
"modalId"=>"delete".$modalName,
"modalRoute"=>($modalRoute??$modalName) . '.destroy',
"modalMaxHeight" => $modalMaxHeight??null,
"modalRouteId"=>$modalRouteId??null,
"modalRouteMethod"=>'delete',
])
<!-- ================================================ -->
@slot('modalHeader')

<h5 class="modal-title text-white" id="{{$modalName}}Label">{{ trans('translation.delete'.$modalName) }}
</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
@endslot
<!-- ================================================ -->
@slot('modalBody')

<div class="modal-body">
    <div class="row">
        {{$slot}}
    <!-- <div class="mb-3">
        <label for="inputNameAr" class=" form-label">{{ trans('translation.oranization-name-ar') }}
            <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="inputNameAr" name="name_ar" placeholder="{{ trans('translation.oranization-name-ar') }}" required>
    </div> -->

    </div>
    <!--end row-->
</div>
@endslot
<!-- ================================================ -->
@slot('modalFooter')

<div class="hstack gap-2 justify-content-end">
    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
    <button type="submit" class="btn btn-danger" id="submit-{{$modalName}}-btn" disabled>{{ trans('translation.delete') }}</button>
</div>
@endslot


@endcomponent

@push('after-scripts')
    <script name="checkEmpty">
        $(document).ready(function(){
            $('#delete' + '{{$modalName}}' + ' .check-empty-input').on('change', function(){
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
                $('#delete' + '{{$modalName}}' + ' .check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#submit-' + '{{$modalName}}' + '-btn').prop('disabled', !flag)
            })

            $('form').on('submit', function(e){
                let sendBtn = $('#submit-' + '{{$modalName}}' + '-btn');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
            })
        });
    </script>
@endpush
