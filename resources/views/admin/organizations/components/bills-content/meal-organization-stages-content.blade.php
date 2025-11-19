<div class="tab-pane fade" id="custom-v-pills-meal-organization-stages" role="tabpanel" aria-labelledby="custom-v-pills-meal-organization-stages-tab">
    <div class="row mt-2">
        <div class="col-lg-12">
            @component('components.organization-header', ['title' => 'meal-organization-stages', 'hide_button' => false])
            @endcomponent

            <x-data-table id="meal-organization-stages-datatable" :columns="$meal_organization_stages_columns"/>
        </div>
        <!--end row-->
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
        $(document).ready(function() {
            localStorage.setItem('goBackHref',location.href);
            // $('.selectPicker').selectpicker({
            //     width: '100%',
            // });
            window.organization_stages_datatable = $('#meal-organization-stages-datatable').DataTable({
                "ajax": {
                    "url": "{{ route('meal-organization-stages.datatable') }}",
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
                        "data": 'stage',
                    },
                    {
                        "data": 'food-name',
                    },
                    {
                        "data": 'status',
                    },
                    {
                        "data": 'done_by',
                    },
                    {
                        "data": 'done_at',
                    },
                    {
                        "data": 'duration',
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

        </script>
@endpush
