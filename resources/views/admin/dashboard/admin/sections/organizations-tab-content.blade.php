<div class="tab-content" id="v-pills-tabContent">
    {{-- <div class="tab-pane fade show active" id="v-pills-organization-0" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="row">
            @php
                $organization = null;
            @endphp
            @include('admin.dashboard.admin.sections.general-info')
        </div>
        <div class="row">
            @include('admin.dashboard.admin.sections.operations')
        </div>
    </div> --}}
    @forelse($all_organizations as $index => $organization)
    <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}" id="v-pills-organization-{{$organization->id}}" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="row">
            @include('admin.dashboard.admin.sections.general-info')
        </div>
        <div class="row">
            @include('admin.dashboard.admin.sections.operations')
        </div>
    </div>
    @empty
    @endforelse
</div>