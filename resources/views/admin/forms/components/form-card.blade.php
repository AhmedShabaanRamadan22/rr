@push('styles')
    <style>
        .square-card-body {
            height: 200px;
            width: 200px;
            margin: 5px;
            /* box-shadow:5px 5px;
                                                                        border: 1px solid transparent; */
        }
.icon-bigger{
            font-size: 32px;
        }
.plus-bigger{
            font-size: 50px;
        }
        .btn-add-section:active,
        .btn-add-section:hover,
        .btn-add-section.focus,
        .btn-add-section.active {
            background-color: #D5D5D5 !important;
            box-shadow:inset 0px 0px 0px 2px #9A9A9A
                /* border: 0.5px #000 !important; */
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background-color: #D5D5D5;
            border-radius: 5px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background:  #9A9A9A; ;
            border-radius: 5px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #929292;
        }
    </style>
@endpush
<div class=" card-body">

    <!-- Nav tabs -->
    <ul class="nav nav-pills nav-justified arrow-navtabs nav-secondary mb-3 gap-2" role="tablist">
        @foreach ($forms->pluck('organization_service.organization')->unique() as $organization)
            <li class="nav-item">
                <a class="nav-link border" data-bs-toggle="tab" href="#organization_{{ $organization->id }}" role="tab"
                    id="organizationTab_{{ $organization->id }}" onclick="tabChanged({{ $organization->id }})">
                    {{ $organization->name_ar }}
                </a>
            </li>
        @endforeach
    </ul>
    <!-- </div> -->


    <!-- Show All Forms -->
    <div class="tab-content ">
        @php
            $previous_item = null;
        @endphp

        @foreach ($forms->sortBy('organization_service.organization_id') as $form)
            <!-- Tab panes -->
            @if ($loop->first)
                <div class="tab-pane fade" id="organization_{{ $form->organization_service->organization->id }}"
                    role="tabpanel">
                @elseif(isset($previous_item) &&
                        $form->organization_service->organization_id != $previous_item->organization_service->organization_id)
                </div>
                <div class="tab-pane fade" id="organization_{{ $form->organization_service->organization->id }}"
                    role="tabpanel">
            @endif

            <div class="card card-light card-form">
                <div class="card-body">
                    <div class="d-flex align-items-center w-100">
                        <div class="d-flex align-items-center w-100 " onclick="toggleSection({{ $form->id }})"
                            style="cursor: pointer">
                            <!-- <a class="form-collapser" data-form-id="{{ $form->id }}"> -->
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-0 d-flex align-items-center">
                                    {{ $form->name }}
                                    <small>( {{ $form->organization_service->service->name }} -
                                        {{ $form->organization_category->category->name }} )</small>
                                    <i
                                        class="bi bi-eye{{ $form->is_visible ? '' : '-slash' }}-fill text-{{ $form->is_visible ? 'primary' : 'danger' }} fs-4 mx-3"></i>
                                </h6>
                            </div>
                            <div class="flex-shrink-0">
                                <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">

                                    <li class="list-inline-item">
                                        <a class="align-middle minimize-card collapsed"
                                            id="form-collapser-{{ $form->id }}" data-bs-toggle="collapse"
                                            href="#form-{{ $form->id }}" role="button" aria-expanded="false"
                                            aria-controls="collapseExample1"
                                            onclick="unCollapsed(this, {{ $form->id }})" onload="loadded(this)">
                                            <i class="mdi mdi-chevron-up align-middle minus icon-bigger"></i>
                                            <i class="mdi mdi-chevron-down align-middle plus icon-bigger"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dropdown float-end">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted "><i
                                        class="mdi mdi-dots-vertical align-middle icon-bigger"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item show-answers" href="{{route('submitted-forms.index')}}" data-form-id="{{ $form->id }}">{{trans('translation.show-answers')}}</a>
                                <a class="dropdown-item form-edit-button" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editFormModal" data-form-id="{{ $form->id }}"
                                    data-form-name="{{ $form->name }}"
                                    data-form-code="{{ $form->code }}"
                                    data-form-description="{{ $form->description }}"
                                    data-form-submissions-times="{{ $form->submissions_times }}"
                                    data-form-submissions-by="{{ $form->submissions_by }}"
                                    data-form-display="{{ $form->display }}"
                                    data-form-visible="{{ $form->is_visible }}">{{trans('translation.edit')}}</a>
                                <a class="dropdown-item formDeleteButton " href="#"
                                    data-form-id="{{ $form->id }}">{{trans('translation.delete')}}</a>
                                <a class="dropdown-item show-form-answers" href="{{route('form-answers.index',$form->id)}}" data-form-id="{{ $form->id }}">{{trans('translation.form-answers')}}</a>
                                <a class="dropdown-item show-form-sectors" href="{{route('form-sectors.show',$form->id)}}" data-form-id="{{ $form->id }}">{{trans('translation.submitted-form-sectors')}}</a>

                            </div>
                        </div>
                    </div>

                    <!-- </a> -->
                </div>
                <div class="card-body collapse  bg-light" id="form-{{ $form->id }}" onclick="unCollapsed(event,{{$form->id}})">
                    <div class="container-fluid flex-nowrap ">
                        <div class="d-flex flex-row flex-nowrap overflow-x-auto ">
                            <a class="  addSectionButton btn p-0" data-bs-toggle="modal"
                                data-bs-target="#addsections" id="{{ $form->id }}"
                                data-form-id="{{ $form->id }}">
                                <div class="card   square-card-body   btn-add-section">
                                    <div class="card-body shadow border  text-center">
                                        <p class="mb-4">
                                            {{trans('translation.add-new-section')}}
                                        </p>

                                        <i class="mdi mdi-plus mdi-lg align-middle plus plus-bigger mt-4"></i>

                                    </div>
                                </div>
                            </a>
                            @foreach ($form->sections as $section)
                                @include('admin.forms.components.section-card')
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @if ($loop->last)
    </div>
    @endif
    @php
        $previous_item = $form;
    @endphp
    <!-- end of col of all froms-->
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            const openedSection = sessionStorage.getItem("section");
            const scrollOffset = sessionStorage.getItem('offsetY');
            const section = document.getElementById(`form-${openedSection}`);
            const tab = sessionStorage.getItem('tab');
            const activeTabClasses = ['active', 'show']
            if (tab != null) {
                document.getElementById(`organization_${tab}`).classList.add('active', 'show');
                document.getElementById(`organizationTab_${tab}`).click();
            } else {
                document.getElementById(`organization_${1}`).classList.add('active', 'show');
                document.getElementById(`organizationTab_${1}`).click();

            }
            section.classList.remove('collapse')
            section.classList.add('show')
            const chevron = document.getElementById(`form-collapser-${openedSection}`);
            chevron.classList.remove('collapsed')
            chevron.setAttribute('aria-expanded', true)
            setTimeout(() => {
                window.scrollTo({
                    top: scrollOffset,
                    behavior: 'instant',
                });
            }, 10);
        });

        function unCollapsed(e, id) {
            sessionStorage.setItem("section", JSON.stringify(id));

        }

        function tabChanged(id) {
            sessionStorage.setItem('tab', JSON.stringify(id));
        }

        function toggleSection(id) {
            const chevron = document.getElementById(`form-collapser-${id}`);
            chevron.click();
        }
    </script>
    @endforeach
</div>
</div>

@push('after-scripts')
    {{-- <script>
        $(document).ready(function(){
            $('.show-answers').on('click', function(){
                let form_id = $(this).attr('data-form-id')
                localStorage.setItem('form_id', form_id);
            })
        })
    </script> --}}
@endpush
