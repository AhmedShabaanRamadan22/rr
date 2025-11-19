
@component('modals.add-modal-template',['modalName'=>'signed_contract', 'modalRoute'=>'admin.signed-contracts'])
    <input type="hidden" name="contract_id" value="" id="contract_id">
    @component('components.inputs.file-input',['columnName'=>'signed_contract','col'=>'6','margin'=>'mb-3', 'name'=>'signed_contract']) @endcomponent
    @component('components.inputs.date-input',['columnName'=>'sign_date','col'=>'6','margin'=>'mb-3']) @endcomponent
@endcomponent

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('#addsigned_contract').on('show.bs.modal', function(e) {
            var contract_id = $(e.relatedTarget).attr('data-contract-id');
            $('#contract_id').val(contract_id)
        });
    });
</script>
@endpush
