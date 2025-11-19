{{-- <div class="modal fade" id="addservices"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <form action="{{route('organization-services.store')}}" method="post">
                @csrf
                <input type="hidden" name="organization_id" id="hidden_organization_id" value="">
                <div class="modal-header">
                    <h6 class="modal-title">{{trans('translation.add-service')}}</h6>
                    <button class="btn-close ml-0" data-bs-dismiss="modal" aria-label="Close" type="button">
                        <!-- <span aria-hidden="true">×</span> -->
                    </button>
                </div>
                <div class="modal-body">
                    <h6>{{trans('translation.services')}}</h6>
                    <!-- Select2 -->
                    <select class="form-control  " name="service_id" id="select_organization_service" placeholder="{{trans('translation.choose-service')}}">
                        <option value="choose_one" disabled selected>{{trans('translation.choose-service')}}</option>
                        @foreach ($services as $service)
                        <option value="{{$service->id}}">{{$service->name}}</option>
                        @endforeach
                    </select>
                    <!-- Select2 -->
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit">{{trans('translation.save-change')}}</button>
                    <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">{{trans('translation.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


{{-- ?? commented to optimize edit organization page --}}
@component('modals.add-modal-template',['modalName'=>'services', 'modalRoute'=>'organization-services'])
    <input type="hidden" name="organization_id" id="hidden_organization_id_service" value="{{$organization->id}}">
    <h6>{{trans('translation.services')}}</h6>
    <!-- Select2 -->
    <select class="form-control selectPicker check-empty-input" name="service_id" id="select_organization_service" placeholder="{{trans('translation.choose-service')}}">
        <option value="choose_one" disabled selected>{{trans('translation.choose-service')}}</option>
        @foreach ($services as $service)
        <option value="{{$service->id}}">{{$service->name}}</option>
        @endforeach
    </select>
@endcomponent  

@push('after-scripts')
<script>
    $(document).ready(function() {
        
        $('#addservices').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            var organizationId = $(e.relatedTarget).data('organization-id');
            var used_service = $(e.relatedTarget).attr('data-services');
            var service_ids = used_service.split(',');
            //populate the textbox
            $(e.currentTarget).find('#hidden_organization_id').val(organizationId);
            $('#select_organization_service option').each(function() {
                $(this).removeClass('d-none');
                if (service_ids.includes($(this).val()) && $(this).val() != "choose_one") {
                    $(this).addClass('d-none');
                }
            });
            $('.selectPicker').selectpicker('destroy');
            $('.selectPicker').selectpicker();
        });

    }); // end document ready
</script>

@endpush