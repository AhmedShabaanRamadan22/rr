@component('modals.add-modal-template',['modalName'=>'organization-stages', 'modalRoute'=>'organization-stages'])
    <div class="col-12">
        <input type="hidden" name="organization_id" id="hidden_organization_id" value="{{$organization->id}}">
        <h6>{{trans('translation.stage')}}</h6>
        <!-- Select2 -->
        <select class="form-control selectPicker check-empty-input mb-3" name="stage_bank_id" id="stage_bank_id" placeholder="{{trans('translation.choose-stage')}}">
            <option value="choose_one" disabled selected>{{trans('translation.choose-stage')}}</option>
            @foreach ($stages as $stage)
                <option value="{{$stage->id}}" data-duration="{{ $stage->duration }}">{{$stage->name}}</option>
            @endforeach
        </select>

        @component('components.inputs.number-input',['columnName'=>'duration','col'=>'12','margin'=>'mb-3','is_required'=>false]) @endcomponent

    </div>
@endcomponent


@push('after-scripts')
<script>
    $(document).ready(function() {

        $('#stage_bank_id').on('change',function(e) {
            var duration = $(this).find(':selected').data('duration');
            $('#input_duration').val(duration);
        })

        $('#addservices').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            var organizationId = $(e.relatedTarget).data('organization-id');
            var used_service = $(e.relatedTarget).attr('data-services');
            var service_ids = used_service.split(',');
            //populate the textbox
            $(e.currentTarget).find('#hidden_organization_id').val(organizationId);
            $('#select_organization_service option').each(function() {
                $(this).removeClass('d-none');
                if (service_ids.includes($(this).val()) && $(this).val() != "choose_one") {
                    $(this).addClass('d-none');
                }
            });
            $('.selectPicker').selectpicker('destroy');
            $('.selectPicker').selectpicker();
        });

    }); // end document ready
</script>

@endpush
