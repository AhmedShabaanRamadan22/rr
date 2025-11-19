{{-- !! add meal by nationality --}}
@component('modals.add-modal-template',['modalName'=>'meals'])
<input type="hidden" name="organization_id" val()="{{$organization->id}}">
@foreach ($columnMeals as $column => $type)
    <div class="col-6">
        @component('components.inputs.'.$type.'-input',['columnName'=>$column,'col'=>'12','margin'=> 'mb-3', 'is_multiple' => $column == 'food_weights' ? 'multiple' : '', "columnOptions" => ($optionMeals??null),"columnSubtextOptions" => ($subtextOptionMeals??null)])
            @if ($column == "end_time")
            <small id="time-range-error" class="d-none text-danger"></small>
            <small id="next-day-error" class="d-none text-info">{{trans('translation.next-day-error')}}</small>
            @endif
        @endcomponent
        

    </div>
@endforeach
@endcomponent

@push('after-scripts')

<script>
    $(document).ready(function(){
        let organizationSectorJson = @json($sectorJson->load('nationality_organization.food_weights'));
        let subtext_options = @json($meal_subtext_options['sector_id']);
        let menus = @json($menu->load('food_weight.food'));
        $('#natioanlity_organization_id_filter').on('change', function(){
            let nationality_organization_id = $(this).val();
            $('#addmeals #sector_id_filter').empty();
            $.each(organizationSectorJson, function(id,sector){
                if(sector.nationality_organization_id == nationality_organization_id){
                    $('#addmeals #sector_id_filter').append($('<option>', {
                        value: sector.id,
                        text: sector.label,
                        'data-subtext': subtext_options[sector.id]
                    }))
                }
            });
            $('#addmeals #sector_id_filter').selectpicker('destroy');
            $('#addmeals #sector_id_filter').selectpicker({width: '100%'});

            AllowedFood = menus.filter((menu)=>{
                return menu.nationality_organization_id == nationality_organization_id;
            }).map((item)=>item.food_weight_id);

            $('#food_weights_filter option').each(function(){
                $(this).hide();
                if(AllowedFood.includes(+$(this).val())){
                    $(this).show();
                }
            })
            $('#food_weights_filter').selectpicker('destroy');
            $('#food_weights_filter').selectpicker({width: '100%'});

            $('#food_weights_filter').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue){
                checkFoodWeightFilter(e, clickedIndex, isSelected, previousValue,$(this));
            })
        })

        let timeRange, formattedTimeRange;
        let errorMessage = $('#time-range-error');
        let nextDay = $('#next-day-error');
        let start = $('#input_start_time');
        let end = $('#input_end_time');

        start.on('change', function() {
            let endTime = new Date('1970-01-01T' + $(this).val());
            endTime.setMinutes(endTime.getMinutes() + 31);

            timeRange = endTime.toTimeString().slice(0, 5);

            let hours = endTime.getHours();
            let minutes = endTime.getMinutes();
            let period = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;

            let formattedTimeRange = hours + ':' + minutes + ' ' + period;

            end.min = formattedTimeRange;

            errorMessage.text("{{trans('translation.end-time-error')}} " + formattedTimeRange);
            checkTime()

        });

        end.on('change', function() {
            checkTime()
        });

        let checkEmpty = () => {
            let isEmpty = false;
            $('#addmeals .check-empty-input').each(function() {
                if (!$(this).is('div')){
                    if($(this).val().length == 0){
                        isEmpty = true;
                        return isEmpty;
                    }
                }
            });
            return isEmpty;
        }

        let checkTime = () => {
            let flag = true;
            if(end.val() == '' || (end.val() < timeRange && end.val() >= start.val())){
                errorMessage.removeClass('d-none')
                flag = false
            }
            else{
                errorMessage.addClass('d-none')
            }
            if(end.val() != '' && end.val() < start.val()){
                nextDay.removeClass('d-none')
            }
            else{
                nextDay.addClass('d-none')
            }
            return flag;
        }
        $('#addmeals .check-empty-input').on('change', function(){
            let flag = !(!checkEmpty() && checkTime())
            $('#submit-meals-btn').prop('disabled', flag)
        })

        $('#food_weights_filter').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue){
            checkFoodWeightFilter(e, clickedIndex, isSelected, previousValue,$(this));
        })
        let checkFoodWeightFilter = (e, clickedIndex, isSelected, previousValue,element) => {

            var selectedOption = element.find('option').eq(clickedIndex);
            var selectedGroup = selectedOption.parent();

            if (isSelected) {
                // Deselect all other options within the same group
                selectedGroup.find('option:selected').not(selectedOption).prop('selected', false);
            }
            else {
                // If the selected option is being deselected, no action needed
                return;
            }
            
            // Update the selectpicker value programmatically
            element.selectpicker('val', element.val());
        }
    }); // end document ready
</script>

@endpush