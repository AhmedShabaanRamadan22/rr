<div class="col-md-{{$col??3}} {{$margin??'mb-2'}}">
    <input type="hidden" id="{{$columnName}}_hj" name="{{$columnName}}_hj" value="{{isset($modelItem) ? $modelItem[$columnName . '_hj']??'' : ''}}">
    <label for="input_{{$columnName}}" class=" form-label">
        {{ trans('translation.'.(str_replace('_','-',$columnName))) }}
        @if (!isset($is_required))
        <span class="text-danger">*</span>
        @endif 
    </label>
    <input type="date" class="form-control {{ !isset($is_required) ? 'check-empty-input':'' }} text-start" id="input_{{$columnName}}" name="{{$columnName}}" placeholder="{{ trans('translation.'.(str_replace('_','-',$columnName))) }}" {{$is_required??'required'}} value="{{$modelItem[$columnName]??""}}" {{$disabled??''}}>
    <small class="text-info"><span id="{{'hijri_' . $columnName}}">{{isset($modelItem[$columnName . '_hj']) ? trans("translation.coresponding-hijri") . $modelItem[$columnName . '_hj']??"" : ''}}</span></small>
</div>

@push('after-scripts')
<script>
    const setHijriDate = (element) => {
        let id = element.attr('id').slice('input_'.length)
        let date = new Date(element.val())
        let day = date.getDate()
        let month = date.getMonth() + 1
        let year = date.getFullYear()
        $.ajax({
            type: "GET",
            url: 'https://api.aladhan.com/v1/gToH/' + day + '-' + month + '-' + year,
            success: function(response, jqXHR, xhr) {
                $('#hijri_' + id).html('{{trans("translation.coresponding-hijri")}}' + response.data.hijri.date)
                $('#' + id + '_hj').val(response.data.hijri.date)
            }
        });
    }

    $('#input_' + '{{$columnName}}').on('change', function(){
        setHijriDate($(this));
    });
</script>
@endpush