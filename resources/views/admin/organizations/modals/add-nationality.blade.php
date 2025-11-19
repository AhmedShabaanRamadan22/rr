@component('modals.add-modal-template',['modalName'=>'nationality', 'modalRoute'=>'nationality-organizations'])
    <input type="hidden" name="organization_id" id="hidden_organization_id" value="{{$organization->id}}">
    <h6>{{trans('translation.nationalities')}}</h6>
    <select class="form-control  selectPicker mb-3 check-empty-input" name="nationality_id" id="select_organization_nationality" data-live-search="true" placeholder="{{ ($nationalities_of_organization = $nationalities->whereNotIn("id",$organization->nationalities->pluck('id')->toArray()) )->count() > 0 ? trans('translation.choose-nationality'):trans('translation.no-choices-available')}}"  {{$nationalities_of_organization->count() > 0 ? '':'disabled'}} required>
        @foreach ($nationalities_of_organization as $nationality)
        <option value="{{$nationality->id}}" data-content="{{$nationality->flag_icon}}<span>{{$nationality->name}}</span>"></option>
        @endforeach
    </select>
@endcomponent   

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('.selectPicker').selectpicker({
            width: '100%',
        });
    }); // end document ready
</script>

@endpush