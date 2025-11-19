<div class="col-xl-2">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">{{trans("translation.organization")}}</h4>
        </div>
        <div class="card-body p-0">
            <div data-simplebar style="max-height: 380px;"class="nav flex-column nav-light nav-pills gap-3"
                id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach ($all_organizations as $index => $org)
                    <a class="nav-link d-flex p-2 gap-3 {{ $loop->first ? 'active' : '' }}"
                        id="v-pills-{{ $index }}-tab" data-bs-toggle="pill" href="#v-pills-{{ $index }}"
                        role="tab" aria-controls="v-pills-{{ $index }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title rounded text-warning fs-2xl">
                                <i class="mdi mdi-office-building-outline text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 align-self-center">
                            <h5 class="text-reset">{{ $org->name }}</h5>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
