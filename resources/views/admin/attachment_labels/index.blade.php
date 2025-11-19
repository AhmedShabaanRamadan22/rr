@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- add new question_type --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :filterColumns="$filterColumns"> 
    <x-slot name="filters">
        @include('admin.attachment_labels.components.filters')
    </x-slot>
</x-crud-model>
@endsection

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('#attachment-label-filter-btn').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "GET",
                url: "{{ route('attachment-labels.datatable') }}",
                data: {
                    type: $('#type_filter').val(),
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    window.attachment_labelsDatatable.ajax.reload();
                },
                error: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });
        });
        $('#attachment-label-reset-btn').click(function() {
            $('.selectpicker').selectpicker('deselectAll');
            window.attachment_labelsDatatable.ajax.reload();
        });

    }); // end document rready
</script>
@endpush
