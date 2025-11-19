{{-- !! add meal by sector --}}
{{-- @component('modals.add-modal-template',['modalName'=>'meals'])
<input type="hidden" name="organization_id" val()="{{$organization->id}}">
@foreach ($columnMeals as $column => $type)
    @if ($column == "organization_id")
        @continue
    @endif
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
        // $('#a-meals-btn').prop('disabled', 'disabled')
        let mealsJson = @json($mealsJson);
        let sectorJson = @json($organization->sectors->load('nationality_organization.food_weights'));
        let notAllowedPeriod, AllowedFood ;

        $('#input_day_date, #sector_id_filter').on('change',function(){

            let daySelected = $('#input_day_date').val();
            let sectorSelected = +$('#sector_id_filter').val();
            if($("#sector_id_filter").val() != "" && $('#input_day_date').val() != ""){
                notAllowedPeriod = mealsJson.filter((meal)=>{
                    return (meal.sector_id == sectorSelected && meal.day_date == daySelected) ;
                }).map((item)=>item.period_id);

                $('#period_id_filter option').each(function(){
                    $(this).hide();
                    if(!notAllowedPeriod.includes(+$(this).val())){
                        $(this).show();
                    }
                })
                $('#period_id_filter').selectpicker('destroy').selectpicker({
                    width: '100%',
                });
            } 
        })

        $('#sector_id_filter').on('change',function(){

            let sectorSelected = +$('#sector_id_filter').val();
            if($("#sector_id_filter").val() != ""){
                AllowedFood = sectorJson.filter((sector)=>{
                    
                    // return (food.nationality_organizations.sectors.sector_id.includes(sectorSelected)) ;
                    return sector.id == sectorSelected;
                })[0].nationality_organization.food_weights.map((item)=>item.id);

                $('#food_weights_filter option').each(function(){
                    $(this).hide();
                    if(AllowedFood.includes(+$(this).val())){
                        $(this).show();
                    }
                })
                $('#food_weights_filter').selectpicker('destroy').selectpicker({
                    width: '100%',
                });
                $('#food_weights_filter').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue){
                    checkFoodWeightFilter(e, clickedIndex, isSelected, previousValue,$(this));
                })
            } 
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

@endpush --}}