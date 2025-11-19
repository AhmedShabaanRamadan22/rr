@component('components.organization-header', ['title' => 'organization-stages', 'showSortButton' => true])
@endcomponent
<div class="row">
    <x-data-table id="stages-organization-datatable" :columns="$organization_stages_columns"/>
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
        $(document).ready(function() {
            // $('.selectPicker').selectpicker({
            //     width: '100%',
            // });
            window.stages_organization_datatable = $('#stages-organization-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('organization-stages.datatable') }}",
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
                // 'createdRow': function(row, data, rowIndex) {
                //     $(row).attr('data-id', data.id);
                //     $(row).attr('data-fine', data.fine.name);
                //     $(row).attr('data-description', data.description);
                //     $(row).attr('data-price', data.price);
                // },
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
                    "data": 'stage-bank-name',
                    },
                    {
                        "data": 'arrangement',
                    },
                    {
                        "data": 'duration',
                    },
                    {
                        "data": 'questions',
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


        $('body').on('click', '.delete-organization_stages', function() {
                var model_id = $(this).attr('data-model-id');
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
                                url: "{{ url('/organization-stages') }}" + "/" + model_id,
                                success: function(response) {
                                    stages_organization_datatable.ajax.reload();
                                    Toast.fire({
                                        icon: "success",
                                        title: response.message ??
                                            "{{ trans('translation.delete-successfully') }}"
                                    });
                                },
                                error: function(response, jqXHR, responseJSON) {
                                    Toast.fire({
                                        icon: "error",
                                        title: response.responseJSON.message ??
                                            "{{ trans('translation.something went wrong') }}"
                                    });
                                },
                            });
                        }
                    });
            })
            </script>

    <!-- Sortable -->
    <script src="{{ URL::asset('build/libs/sortablejs/Sortable.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/nestable.init.js') }}"></script>
    <script>
        $(document).on('shown.bs.modal',function() {
            $.ajax({
                type: "GET",
                url: "{{ route('organization-stages.datatable') }}",
                data: {

                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    let data= response.data
                    $('#sortable').empty();

                    if (data.length > 1) {
                        let sendBtn = $('#submit-sort-organization-stages-datatable-btn');
                        sendBtn.prop('disabled', false)
                    }

                    data.forEach(element => {
                            $('#sortable').append(
                                `<div class="list-group-item nested-1" data-id="`+element.id+`">`+element.sortable_name+`</div>`
                            )
                        });

                },
                error: function(jqXHR, responseJSON) {
                },
            });
        })
    </script>
@endpush
