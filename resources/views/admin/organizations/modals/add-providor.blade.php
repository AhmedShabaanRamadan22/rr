@component('modals.add-modal-template',['modalName'=>'providors','modalRoute'=>'order-sectors', 'modalMaxHeight'=>'90vh'])
<input type="hidden" name="organization_id" value="{{$organization->id}}">
<h6>{{trans('translation.providors')}}</h6>
<select class="form-control  selectPicker mb-3  check-empty-input" name="order_id" id="select_order" data-live-search="true" required placeholder="{{ ($accepted_orders = $organization->orders)->count() > 0 ? trans('translation.choose-providor'):trans('translation.no-choices-available')}}" {{$accepted_orders->count() > 0 ? '':'disabled'}}>
    @foreach ($accepted_orders as $order)
    <option value="{{$order->id}}" data-subtext=" {{$order->code??''}}"  showSubtext="true">{{$order->facility->name??'-'}}</option>
    @endforeach
</select>
<div id="providor-card" class="d-none mb-3 p-3">
            @component('components.data-row', ['id'=>'code'])@endcomponent
            @component('components.data-row', ['id'=>'facility-name'])@endcomponent
            @component('components.data-row', ['id'=>'registration-number'])@endcomponent

            @component('admin.facilities.components.date-row', ['id'=>'expiration-date'])@endcomponent
            @component('admin.facilities.components.date-row', ['id'=>'license-expiration-date'])@endcomponent


            @component('components.data-row', ['id'=>'kitchen-space'])@endcomponent

            @component('components.data-row', ['id'=>'user-name'])@endcomponent
            @component('components.data-row', ['id'=>'phone'])@endcomponent
            @component('components.data-row', ['id'=>'email'])@endcomponent

            @component('components.data-row', ['id'=>'service-name'])@endcomponent

</div>
<h6>{{trans('translation.sectors')}}</h6>
<select class="form-control  selectPicker mb-3 check-empty-input" name="sector_id" id="select_sector_id" data-live-search="true" required placeholder="{{ ($sectors = $organization->sectors)->count() > 0 ? trans('translation.choose-sector'):trans('translation.no-choices-available')}}" {{$sectors->count() > 0 ? '':'disabled'}}>
    @foreach ($sectors as $sector)
    <option value="{{$sector->id}}">{{$sector->label??'-'}}</option>
    @endforeach
</select>
<div id="sector-card" class="d-none mb-3">
            @component('components.data-row', ['id'=>'label'])@endcomponent
            @component('components.data-row', ['id'=>'sight'])@endcomponent

            @component('components.data-row', ['id'=>'guest-value'])@endcomponent
            @component('components.data-row', ['id'=>'guest-quantity'])@endcomponent
            @component('components.data-row', ['id'=>'classification-id'])@endcomponent
            @component('components.data-row', ['id'=>'nationality-name'])@endcomponent
</div>

<small class="text-info">{{trans('translation.add-meal-in-meal-section')}}</small>
@endcomponent


@push('after-scripts')

<script name="add-provider-modal">
    $(document).ready(function() {
        let acceptedOrderObject = @json($accepted_orders->load(['organization_service.service']));
        let orderSectors = @json($accepted_orders->pluck('order_sectors')->flatten(1));
        let acceptedOrder = Object.values(acceptedOrderObject);
        let sectors = @json($sectors->load(['nationality_organization.nationality','classification']));
        let notAllowedSector;

        $('#select_order').on('change',function(){
            $('#providor-card').addClass('d-none');
            let orderSelected = $(this);
            let orderSelectedValue = orderSelected.val();

            if(orderSelectedValue != "" || orderSelectedValue != null){
                let orderInfo = acceptedOrder.filter((item) => {
                    return item.id == orderSelectedValue;
                })[0];
                $('#facility-name').text(orderInfo.facility.name);
                $('#registration-number').text(orderInfo.facility.registration_number);
                $('#expiration-date').text(orderInfo.facility.end_date);
                $('#license-expiration-date').text(orderInfo.facility.license_expired);
                $('#user-name').text(orderInfo.user.name);
                $('#phone').text(orderInfo.user.phone);
                $('#email').text(orderInfo.user.email);
                $('#service-name').text(orderInfo.organization_service.service.name);
                $('#kitchen-space').text(orderInfo.facility.kitchen_space);
                $('#code').text("ORD" + (""+orderInfo.id).padStart(5,"0"));

                $('#providor-card').removeClass('d-none');

                notAllowedSector = orderSectors.filter((item)=>{
                    return item.order_id == orderSelectedValue && item.archived_at === null && item.deleted_at === null;
                }).map((item) => { return item.sector_id });
                $('#select_sector_id option').each(function(){
                    $(this).show();
                    if(notAllowedSector.includes(+$(this).val())){
                        $(this).hide();
                    }
                })

                $('#select_sector_id').selectpicker('destroy').selectpicker({
                    width:'100%',
                })
            }


        });
        $('#select_sector_id').on('change',function(){
            $('#sector-card').addClass('d-none');
            let sectorSelected = $(this);
            let sectorSelectedValue = sectorSelected.val();

            if(sectorSelectedValue != "" || sectorSelectedValue != null){
                let sectorInfo = sectors.filter((item) => {
                    return item.id == sectorSelectedValue;
                })[0];
                $('#label').text(sectorInfo.label);
                $('#sight').text(sectorInfo.sight);
                $('#guest-value').text(sectorInfo.classification.guest_value);
                $('#guest-quantity').text(sectorInfo.guest_quantity);
                $('#classification-id').text(sectorInfo.classification.code);
                $('#nationality-name').text(sectorInfo.nationality_organization.nationality.name);

                $('#sector-card').removeClass('d-none');
            }


        });


    }); // end document ready
</script>

@endpush
