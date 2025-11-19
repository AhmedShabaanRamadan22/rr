@component('modals.add-modal-template',['modalName'=>'menus', 'modalRoute'=>'nationality-organizations'])
    <input type="hidden" name="organization_id" id="hidden_organization_id" value="{{$organization->id}}">
    <h6>{{trans('translation.nationalities')}}</h6>
    {{-- @component('components.inputs.select-input',['columnName'=>'nationality_id','col'=>'12','margin'=>'mb-3', 'modelItem'=>$organization, 'columnOptions' => $columnOptions]) @endcomponent --}}
    <select class="form-control  selectPicker mb-3 check-empty-input" name="nationality_id" id="select_organization_nationality" data-live-search="true" placeholder="{{ ($nationalities_of_organization = $nationalities->whereNotIn("id",$organization->nationalities->pluck('id')->toArray()) )->count() > 0 ? trans('translation.choose-nationality'):trans('translation.no-choices-available')}}"  {{$nationalities_of_organization->count() > 0 ? '':'disabled'}} required>
        @foreach ($nationalities_of_organization as $nationality)
        <option value="{{$nationality->id}}" data-content="{{$nationality->flag_icon}}<span>{{$nationality->name}}</span>"></option>
        @endforeach
    </select>
    <h6>{{trans('translation.food')}}</h6>
    {{-- <select class="form-control  selectPicker check-empty-input" name="food_weight_id[]" id="select_food_weight" multiple placeholder="{{ trans('translation.choose-foods') }}"  data-actions-box="true"  data-live-search="true" required>
        @foreach ($food_weights as $food_weight)
        <option value="{{$food_weight->id}}" data-subtext="{{$food_weight->food->food_type->name}}" data-food-id="{{$food_weight->food->id}}"> {{$food_weight->food_name}} </option>
        @endforeach
    </select> --}}

    <select class="form-control selectpicker check-empty-input mt-1" name="food_weight_id[]" id="food_weight_id_filter" multiple data-live-search="true" title="{{trans('translation.choose-food')}}" required>
        @php
            $previous_item = null;
        @endphp
        @forelse ($columnOptions['food_weight'] as $food_weight)
            @if(!isset($previous_item))
                <optgroup label="{{ $food_weight['option_group_label'] }}">
            @elseif(isset($previous_item) && $food_weight['option_group_label'] != $previous_item['option_group_label'])
                </optgroup>
                <optgroup label="{{ $food_weight['option_group_label'] }}">
            @endif
            <option value="{{ $food_weight['id'] }}">
                {{ $food_weight['name'] }}
            </option>
            @php
                $previous_item = $food_weight;
            @endphp
            @empty
            <option value="" disabled>{{trans('translation.no-data')}}</option>
        @endforelse
        </optgroup>
    </select>
@endcomponent   

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });

        // $('#select_food_weight').on('change',function(e){
        //     let selected_foods = $('#select_food_weight').find(':selected');
        //     $.each(selected_foods, function(key, value){
        //         let all_same_food = $('#select_food_weight option[data-food-id="' + $(value).attr('data-food-id') + '"]');
        //         $.each(all_same_food, function(key, value){
        //             $(value).prop('disabled',!$(value).prop('disabled'));
        //         })
        //         // $('#select_food_weight').selectpicker('fresh');
                
        //     });
        //     $('#select_food_weight').selectpicker('destroy');
        //     $('#select_food_weight').selectpicker();


        // });

    }); // end document ready
</script>

@endpush