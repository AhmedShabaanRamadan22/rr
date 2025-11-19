@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
    <!-- Content -->
    @component('components.section-header', ['title' => 'fine-organizations'])@endcomponent
    <x-data-table id="fine-organizations-datatable" :columns="$fine_organization_columns" />
    <!-- End Content -->
@endsection

@section('modals')
    @include('admin.organizations.modals.add-fine')
    @include('admin.organizations.modals.edit-fine-organization')
    
@endsection

@push('after-scripts')
    <script>
             
        $(document).ready(function() {
            window.fine_organization_datatable = $('#fine-organizations-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.fine-organizations.datatable') }}",
                    "data": function(d) {
                        d.organization_id = {{ $organization->id }};
                    },
                },
                language: datatable_localized,
                rowId: 'id',
                "drawCallback": function(settings) {
                    $('.selectpicker').selectpicker({
                        width: '100%',
                    });
                },
                'createdRow': function(row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                    $(row).attr('data-fine-bank-id', data.fine_bank.id);
                    $(row).attr('data-fine-name', data.fine_bank.name);
                    $(row).attr('data-description', data.description);
                    $(row).attr('data-price', data.price);
                },
                'stateSave': true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                "columns": [{
                        "data": 'id',
                        render: (data, type, row, meta) => {
                            return ++meta.row;
                        }
                    },
                    {
                        "data": 'fine_bank_name',
                    },
                    {
                        "data": 'price',
                    },
                    {
                        "data": 'description',
                    },
                    {
                        "data": 'action',
                    },
                ],
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });
        });

        $(document.body).on('click', '.deletefine_organizations', function(e) {
            let deleteBtn = $(this);
            let model_id = $(this).attr('data-model-id');
            var row = deleteBtn.closest('tr');
            console.log(row);
            console.log(row.attr('data-fine-name'));
            Swal
                .fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        // deleteBtn.closest('form').submit()
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('/fine-organizations') }}" + "/" + model_id,
                            success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                                window.fine_organization_datatable.ajax.reload();

                                $('#fine_bank_id_filter').append($('<option>',{
                                    value: row.attr('data-fine-bank-id'),
                                    text: row.attr('data-fine-name')
                                }));

                                $('#fine_bank_id_filter').selectpicker('destroy').selectpicker({
                                    width: '100%',
                                });
                            },

                            error: function(jqXHR, responseJSON, response) {
                                Toast.fire({
                                    icon: "error",
                                    title: jqXHR.responseJSON.message
                                });

                            },
                        });
                    }
                });
        })

    </script>
@endpush
