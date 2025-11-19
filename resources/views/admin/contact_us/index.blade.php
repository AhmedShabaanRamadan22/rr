@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions"/>


@component('admin.contact_us.modals.show-modal-template', [
'modalName' => $tableName,
'modalRoute' => str_replace('_', '-', $tableName),
])
    @foreach ($columnInputs as $key => $value)
        <x-row-info id="{{ $key }}"
                    label="{{ trans('translation.' . $key) }}">{{ $key ?? trans('translation.no-data') }}</x-row-info>
    @endforeach
@endcomponent

@push('after-scripts')

    <script>
        $('body').on('click', '.show' + '{{$tableName}}', function () {
            // get the data-model-id attribute value
            var model_id = $(this).attr('data-model-id');

            $.ajax({
                url: "{{ url('/' . str_replace('_', '-', $tableName)) }}" + "/" + model_id,
                type: 'GET',
                success: function (data) {
                    $.each(data, function (key, value) {
                        var infoElement = $('[id="' + key + '"]');
                        infoElement.text(value);
                    });

                    $('#show' + '{{$tableName}}').modal('show');
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        });

    </script>
@endpush

@endsection
