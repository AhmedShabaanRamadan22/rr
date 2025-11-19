
@component('components.organization-header', ['title' => 'meals', 'disabled' => ($has_sectors = $organization->has_sectors) ? null :'disabled'])
<small class=" text-primary my-auto {{$has_sectors ? 'd-none':''}}">{{trans('translation.please-add-sector-first')}}!</small>
@endcomponent

<div class="row">
    <x-data-table id="meals-datatable" :columns="$meal_columns"/>
</div>


@push('after-scripts')
     <script>
        $(document.body).on('click', '.deletemeals', function(e) {
                    let deleteBtn = $(this);
                    let model_id = $(this).attr('data-model-id');
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
                                    url: "{{ url('/meals') }}" + "/" + model_id,
                                    success: function(response) {
                                        $('#'+ model_id).remove();
                                        Toast.fire({
                                            icon: "success",
                                            title: "{{ trans('translation.Deleted successfully') }}"
                                        });

                                    },
                                    error: function(jqXHR, responseJSON) {
                                        Toast.fire({
                                            icon: "error",
                                            title: jqXHR.responseJSON.message ?? "{{ trans('translation.something went wrong!') }}"
                                        });

                                    },
                                });
                            }
                        });
        })

        $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });

        window.mealDatatable = $('#meals-datatable').DataTable({
            "ajax": {
                "url": "{{ route('meals.datatable') }}",
                "data": function(d) {
                    d.organization_id = {{$organization->id}};
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
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            'createdRow': function(row, data, rowIndex) {
                $(row).attr('data-id', data.id);
            },
            "columns": [

                @foreach ($meal_columns as $key => $column)
                    @if ($key == 'id')
                        {
                            data: 'id',
                            render:  (data, type, row, meta) => { return ++meta.row; }
                        },
                        @elseif ($key == 'collapser')
                            @continue;
                        @else
                    {
                        data: '{{ $key }}',
                        className: ' text-center align-middle',
                    },
                    @endif
                @endforeach
            ],
            buttons: ['csv', 'excel'],
            dom: 'lfritpB',
            "ordering": false,
        });
    });
    </script>

<!-- SelectPicker -->
<script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<script>
    function changeSelectPickerMeal(select) {

        var select = $(select);
        Swal
            .fire(window.confirmChangeStatusPopupSetup).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/meal-status') }}",
                        data: {
                            status_id: select.val(),
                            meal_id: select.attr('data-meal-id')
                        },
                        dataType: "json",
                        success: function(response, jqXHR, xhr) {
                                window.mealDatatable.ajax.reload();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.Updated successfuly') }}"
                                });
                            },
                            error:function(response, jqXHR, xhr) {
                                window.mealDatatable.ajax.reload();
                                Toast.fire({
                                    icon: "error",
                                    title: "{{ trans('translation.You dont have permission') }}"
                                });
                            },
                    });
                } else {
                    select.selectpicker('destroy');
                    select.val(select.attr('data-status-id'));
                    select.selectpicker({
                        width: '100%',
                    });
                }
            });
    }
</script>
@endpush

