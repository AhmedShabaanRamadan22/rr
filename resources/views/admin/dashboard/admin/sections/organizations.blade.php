<div class="col-xl-12">

    <div class="p-2 card card-height-100 border-0 overflow-hidden mr-3">
        <div class="card-body p-0">
            <div class="nav nav-pills d-flex flex-row flex-wrap" id="v-pills-tab" role="tablist"
                aria-orientation="horizontal">
                @foreach ($all_organizations as $index => $organization)
                    <a class="nav-link d-flex align-items-center flex-grow-1 p-2 {{ $loop->first ? 'active' : '' }}"
                        id="v-pills-{{ $organization->id }}-tab" data-bs-toggle="tab"
                        href="#v-pills-organization-{{ $organization->id }}" role="tab"
                        aria-controls="v-pills-organization-{{ $organization->id }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title rounded bg-light fs-2xl">
                                <i class="mdi mdi-office-building-outline text-primary"></i>
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h5 class="text-reset mb-0">{{ $organization->name }}</h5>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
