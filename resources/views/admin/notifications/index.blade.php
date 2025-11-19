@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :showAddButton="false" /> 

@endsection


@push('after-scripts')
    <script>
        $(document).ready(function(){
            $('body').on('click','.switchRead',function(){
                let notificationId = $(this).attr('data-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ url("notifications-switch-read") }}/' + notificationId,
                    data: {
                    },
                    dataType: "json",
                    success: function(response, jqXHR, xhr) {
                        window.notificationsDatatable.ajax.reload(null, false);
                        Toast.fire({
                            icon: "success",
                            title: "{{ trans('translation.Updated successfuly') }}"
                        });
                    },
                    error:function(response, jqXHR, xhr) {
                        window.notificationsDatatable.ajax.reload();
                        Toast.fire({
                            icon: "error",
                            title: "{{ trans('translation.something went wrong!') }}"
                        });
                    },
                });
            })
        })
    </script>
@endpush