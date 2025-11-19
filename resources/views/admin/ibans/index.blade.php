@extends('layouts.master')
@section('title', $pageTitle)

@section('content')

{{-- index  --}}
<x-crud-model :tableName="$tableName" :columns="$columns" :columnInputs="$columnInputs" :pageTitle="$pageTitle" :columnOptions="$columnOptions" :columnSubtextOptions="$columnSubtextOptions"/> 

@endsection

@push('after-scripts')
    <script>
        $(document).ready(function(){
            retrieveSelect($('#addibans #ibanable_id_filter'))
            $('#addibans #ibanable_type_filter').on('change', function(){
                retrieveSelect($('#addibans #ibanable_id_filter'))
            })
        })
        function retrieveSelect(selector){
            let type = $('#addibans #ibanable_type_filter').val()
            let emptyFlag = true
            let lists = @json($columnOptions);
            $(selector).empty()
            if(type != ''){
                $.each(lists[type], function(key, value) {
                    $(selector).append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
                emptyFlag = false
            }
            if(emptyFlag){
                $(selector).attr('title','{{trans("translation.no-data")}}');
                $(selector).prop('disabled',true);
            }else{
                $(selector).attr('title',"{{trans('translation.choose-one')}}");
                $(selector).prop('disabled',false);
            }
            $(selector).selectpicker('destroy').selectpicker({});

            return emptyFlag;
        }
    </script>
@endpush