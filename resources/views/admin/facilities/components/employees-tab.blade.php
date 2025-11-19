<x-data-table id="emplyees-datatable" :columns="$columns" />


{{-- <div class="row">
    @forelse ($facility_employees as $employee)
    <div class="col-4">
        <div class="card border border-light rounded">
            <div class="card-header">
                <h3 class="card-title text-primary">{{$employee->name}}</h3>
            </div>


            <div class="card-body">
                <x-row-info id="name-{{$employee->id}}" label="{{ trans('translation.position') }}">{{$employee->facility_employee_position->name}}</x-row-info>
                <x-row-info id="national-id-{{$employee->id}}" label="{{ trans('translation.national-id') }}">{{$employee->national_id}}</x-row-info>
                <div class="row mt-3">
                    @isset($employee->attachment_work_card_photo->url)
                    <div class="col-6">
                        <a href="{{$employee->attachment_work_card_photo->url}}" target="_blank" class="btn btn-outline-primary d-block">{{trans('translation.work-card')}}</a>
                    </div>
                    @endisset
                    @isset($employee->attachment_health_photo->url)
                    <div class="col-6">
                        <a href="{{$employee->attachment_health_photo->url}}" target="_blank" class="btn btn-outline-primary d-block">{{trans('translation.work-card')}}</a>
                    </div>
                    @endisset
                </div>
            </div>
            <div class="card-body">
            </div>

        </div>
    </div>
    @empty
        {{trans('translation.no-data')}}
    @endforelse
</div> --}}