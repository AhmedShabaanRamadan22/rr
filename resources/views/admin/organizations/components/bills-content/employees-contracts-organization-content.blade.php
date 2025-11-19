{{-- ?? commented to optimize edit organization page --}}
{{-- @component('components.section-header', ['title' => 'employee-contracts', 'disabled' => $organization->has_employee_contract_template ? null : 'disabled', 'disabled_message' => trans('translation.no-contract-template')])@endcomponent
<div class="p-4">
    <!-- Tab panes -->
    <div class="tab-content text-muted">
        <x-data-table id="employee-contracts-datatable" :columns="$employee_contract_columns" />
    </div>
</div>


@push('after-scripts')
<script>
    // reload the page then fire the toast
    if (localStorage.getItem('reloadPending') != null) {
        let msg = localStorage.getItem('reloadPending');
        localStorage.removeItem('reloadPending');
        // Display the toast after the reload
        Toast.fire({
            icon: "success",
            title: msg
        });
    }
</script>
<script>
    $(document).ready(function(){
        window.employee_contracts = $('#employee-contracts-datatable').DataTable({
            "ajax": {
                "url": "{{ route('admin.employee-contracts.datatable') }}",
                "data": function(d) {
                    d.organization_id = {{ $organization->id }};
                },
                complete: function(data) {}
            },
            language: datatable_localized,
            rowId: 'id',
            "drawCallback": function(settings) {
                $('.selectpicker').selectpicker({
                    width: '100%',
                });
            },
            'stateSave': true,
            'createdRow': function(row, data, rowIndex) {
                $(row).attr('data-id', data.id);
            },
            "columns": [{
                    "data": 'id',
                    render:  (data, type, row, meta) => { return ++meta.row; }
                },
                @foreach ($employee_contract_columns as $key => $column)
                    @if ($key == 'id')
                        @continue;
                    @else
                    {
                        data: '{{ $key }}',
                    },
                    @endif
                @endforeach
            ],
            buttons: ['csv', 'excel'],
            dom: 'lfritpB',
            "ordering": false,
        });
        $(document.body).on('click', '.delete-employee-contract',function(e){
            let contract_id = $(this).attr('data-contract-id')
            // ' . (route('admin.contracts.destroy', $contract->id)) . '
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        setLoading(true);
                        $.ajaxSetup({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('admin/contracts/destroy') }}" + "/" + contract_id,
                            success: function(response) {
                                localStorage.setItem('reloadPending', "{{trans('translation.delete-successfully')}}");
                                // Reload the page immediately
                                window.location.reload();
                                setLoading(false);
                            },
                            error: function(jqXHR, responseJSON) {
                                setLoading(false);
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.something went wrong!') }}"
                                });
    
                            },
                        });
                    }
                });
        })
    })

</script>
@endpush --}}
