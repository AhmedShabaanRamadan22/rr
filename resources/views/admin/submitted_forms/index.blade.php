@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :filterColumns="$filterColumns"
:pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions" :showAddButton=false>
    <x-slot name="filters">
        @include('admin.submitted_forms.components.filters')
    </x-slot>
</x-crud-model>

@endsection

@push('after-scripts')
    @vite(['resources/js/bootstrap.js'])
    <script data-name="submitted-forms">

        const filter = (select, value) => {
            console.log(select, value)
            select.val(value)
            select.selectpicker('destroy')
            select.selectpicker()
            $('#submitted-forms-filter-btn').click()
        }

        $(document).ready(function() {
            $('#submitted-forms-filter-btn').click(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "GET",
                    url: "{{ route('submitted-forms.datatable') }}",
                    data: {
                        order_sector_id: $('#order_sector_id_filter').val(),
                        user_id: $('#user_id_filter').val(),
                        organization_id: $('#organization_id_filter').val(),
                        category_id: $('#category_id_filter').val(),
                        form_id: $('#form_id_filter').val(),
                        isPaginated: true,
                    },
                    serverSide: true,
                    dataType: "json",
                    success: function(response, jqXHR, xhr) {
                        window.submitted_formsDatatable.ajax.reload();
                    },
                    error: function(response, jqXHR, xhr) {
                        Toast.fire({
                            icon: "error",
                            title: "{{ trans('translation.something went wrong') }}"
                        });
                    },
                });
            });
            $('#submitted-forms-reset-btn').click(function() {
                $('.selectpicker').selectpicker('deselectAll');
                window.submitted_formsDatatable.ajax.reload();
            });

            @if(session()->get('submitted_forms_user_id'))
                filter($('#user_id_filter'), "{{session()->get('submitted_forms_user_id')}}")
            @endif

            @if(session()->get('submitted_forms_order_sector_id'))
                filter($('#order_sector_id_filter'), "{{session()->get('submitted_forms_order_sector_id')}}")
            @endif

            if(localStorage.getItem('form_id') != null){
                $('#form_id_filter').val(localStorage.getItem('form_id'))
                $('#form_id_filter').selectpicker('destroy')
                $('#form_id_filter').selectpicker()
                $('#submitted-forms-filter-btn').click()

                // localStorage.removeItem('form_id')
            }

            window.Echo.channel('ModelCRUD-changes').listen('.SubmittedForm-changes',function(data) {
                window.submitted_formsDatatable.ajax.reload();
            });

        }); // end document rready


    </script>
@endpush
