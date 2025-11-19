@component('components.section-header', ['title' => 'menus'])@endcomponent

<x-data-table id="nationality-organizations-datatable" :columns="$nationalities_organization_columns"/>

@push('after-scripts')
<!-- nationality organization -->
    <script>
        $(document).ready(function() {
            window.nationalitiesOrganizationDatatable = $('#nationality-organizations-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('admin.nationality-organizations.datatable') }}",
                    "data": function(d) {
                        d.organization_id = '{{$organization->id}}';
                    },
                },
                language: datatable_localized,
                rowId: 'id',
                'stateSave': true,
                'createdRow': function(row, data, rowIndex) {
                    $(row).attr('data-id', data.id);
                },
                "columns": [{
                        "data": 'id',
                        render:  (data, type, row, meta) => { return ++meta.row; }
                    },
                    {
                        "data": 'nationality-name',
                    },
                    {
                        "data": 'flag',
                    },
                    {
                        "data": 'menu',
                    },
                    {
                        "data": 'action',
                    },
                ],
                buttons: ['csv', 'excel'],
                dom: 'lfritpB',
                "ordering": false,
            });
        }); //end ready


        // $(document.body).on('click', '.edit_nationality', function(e) {
        //     let deleteBtn = $(this);
        //     Swal
        //         .fire(window.deleteWarningPopupSetup).then((result) => {
        //             if (result.isConfirmed) {
        //                 deleteBtn.closest('form').submit()
        //             }
        //         });
        // })
        $(document.body).on('click', '.delete_nationality', function(e) {
            let deleteBtn = $(this);
            let nationality_id = $(this).attr('data-nationality-id');
            Swal
            .fire(window.deleteWarningPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'DELETE',
                        url: "{{ url('nationality-organizations') }}/" + nationality_id,
                        success: function(response) {
                                Toast.fire({
                                    icon: "success",
                                    title: response.message
                                });
                                window.nationalitiesOrganizationDatatable.ajax.reload();
                            },
                            error: function(jqXHR, responseJSON) {
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
