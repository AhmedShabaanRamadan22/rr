@extends('layouts.master')
@section('title')
    @lang('translation.profile')
@endsection
@section('content')
    @component('components.breadcrumb', ['pageTitle' => trans('translation.profile')])
    @endcomponent

    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center border-bottom border-dashed">
                        @if ($organization->logo)
                            <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}" alt=""
                                class="avatar-lg rounded-circle p-1 img-thumbnail">
                        @else
                            <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}" alt=""
                                class="avatar-lg rounded-circle p-1 img-thumbnail">
                        @endif
                        <div class="my-3">
                            <h5>{{ $user->name }} @if ($user->is_verified)
                                    <i class="bi bi-patch-check-fill align-baseline text-primary-emphasis ms-1"></i>
                                @endif
                            </h5>
                            <p class="text-primary badge bg-primary-subtle">{{ $organization->name_ar }}</p>
                        </div>
                        <div>
                            @foreach ($organization->services as $service)
                                <p class="text-info badge bg-info-subtle">{{ $service->name }}</p>
                            @endforeach
                        </div>
                        <div class="d-flex gap-2 py-4">
                            @if ($organization->phone)
                                <a href="{{ 'https://api.whatsapp.com/send?phone=' . $organization->phone }}"
                                    target="_blank" class="btn btn-outline-primary text-truncate w-100"><i
                                        class="bi bi-whatsapp align-baseline me-1"></i>
                                    {{ trans('translation.contact-us') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="border-bottom border-dashed py-4">
                        <h5 class="card-title mb-3">{{ trans('translation.personal-info') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.name') }}</th>
                                        <td class="text-muted text-end">{{ is_found($user->name) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.designation') }}</th>
                                        <td class="text-muted text-end">{{ is_found($user->role_name) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.phone') }}</th>
                                        <td class="text-muted text-end">{{ '0' . is_found($user->phone) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.email') }}</th>
                                        <td class="text-muted text-end">{{ is_found($user->email) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.birth') }}</th>
                                        <td class="text-muted text-end">
                                            {{ $user->birthday ? $user->age->format('d-m-Y') : trans('translation.not_found') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.organization-website') }}
                                        </th>
                                        <td class="text-muted text-end"><a href='http://{{ $organization->domain }}'
                                                target="_blank">{{ $organization->domain }}</a></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.organization-color') }}
                                        </th>
                                        @if ($organization->primary_color)
                                            <td class="text-muted text-end">
                                                <div class="badge"
                                                    style="background-color: {{ $organization->primary_color }};color:{{ $organization->primary_color }}">
                                                    ل</div>

                                            </td>
                                        @else
                                            <td class="text-muted text-end">
                                                {{ trans('translation.not_found') }}
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.organization-esnad') }}
                                        </th>
                                        <td class="text-muted text-end">
                                            {{ $organization->has_esnad == true ? trans('translation.translation.yes') : trans('translation.translation.no') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">{{ trans('translation.join-date') }}</th>
                                        <td class="text-muted text-end">{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="border-bottom border-dashed py-4">
                        {{-- المرفقات المطلوبة --}}
                        <h5 class="card-title mb-3">{{ trans('translation.organization-documents') }}</h5>
                        <div class="vstack gap-3">
                            @forelse ($organization->organization_attachments_labels as $attachment)
                                <div class="d-flex gap-2 align-items-center position-relative">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm border border rounded">
                                            <div class="avatar-title bg-body-secondary text-secondary rounded fs-lg">
                                                <i class="bi bi-bookmarks"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6>{{ $attachment->placeholder }}</h6>
                                        <p class="text-muted mb-0">{{ $attachment->created_at }}</p>
                                    </div>
                                </div>
                            @empty <div class="text-center">{{ trans('translation.not-found') }}</div>
                            @endforelse
                        </div>
                    </div>
                    <div class=" py-4">
                        {{-- المشاريع المطلوبة --}}
                        <h5 class="card-title mb-3">{{ trans('translation.organization-categories') }}</h5>
                        <div class="vstack gap-3">
                            @forelse ($organization->categories as $category)
                                <div class="d-flex gap-2 align-items-center position-relative">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm border border rounded">
                                            <div class="avatar-title bg-body-secondary text-secondary rounded fs-lg">
                                                <i class="bi bi-layers"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6>{{ $category->name }}</h6>
                                        <p class="text-muted mb-0">رمز المشروع: <span class="px-2 text-bg-primary">
                                                {{ $category->code }}
                                            </span></p>
                                    </div>
                                </div>
                            @empty <div class="text-center">{{ trans('translation.not-found') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->

        <div class="col-xl-9">
            <div class="row align-items-center g-3 mb-3">
                <div class="col-md order-1">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills arrow-navtabs nav-secondary gap-2 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                {{ trans('translation.overview') }}
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#activities" role="tab">
                                {{ trans('translation.activities') }}
                            </a>
                        </li> --}}

                    </ul>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="tab-content">
                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class=" border-bottom border-dashed">
                                <div class="row">
                                    <div class="col-xxl-9 mb-4">
                                        <h5 class="card-title mb-3">عن {{ $organization->name_ar }}</h5>
                                        <p class="text-muted mb-2"> {!! is_found($organization->about_us) !!}</p>
                                    </div>
                                    <div class="col-xxl-3 row justify-content-center ">
                                        <div class="">
                                            <div class="card overflow-hidden">
                                                <div>
                                                    <img id="organization-backgroundImage"
                                                        src="{{ $organization->background_image ?? URL::asset('build/images/users/32/background_image.png') }}"
                                                        alt=""
                                                        class="card-img-top profile-wid-img object-fit-cover"
                                                        style="height: 200px;">
                                                </div>
                                                <div class="card-body pt-0 mt-n5 ">
                                                    <div class="text-center">
                                                        <div class="profile-user position-relative d-inline-block mx-auto">
                                                            <img id="organization-img"
                                                                src="{{ $organization->logo ?? URL::asset('build/images/users/32/logo.png') }}"
                                                                alt=""
                                                                class="avatar-lg rounded-circle object-fit-cover border-0 img-thumbnail user-profile-image">
                                                        </div>
                                                        <div class="mt-3"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="py-4 border-bottom border-dashed">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 class="card-title mb-3">{{ trans('translation.nationalities') }}</h5>
                                        <div class="d-flex flex-wrap gap-2">
                                            @forelse ($organization->countries as $country)
                                                <span
                                                    class="badge bg-primary-subtle text-primary">{{ app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en }}</span>

                                            @empty <span
                                                    class="badge bg-primary-subtle text-primary">{{ trans('translation.not_found') }}</span>
                                            @endforelse
                                            {{-- <span
                                                class="badge bg-primary-subtle text-primary">{{ $organization->countries }}</span>
                                            <span class="badge bg-primary-subtle text-primary">German</span>
                                            <span class="badge bg-primary-subtle text-primary">Arabic</span>
                                            <span class="badge bg-primary-subtle text-primary">Italiana</span> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="py-4 mb-4">
                                <h5 class="card-title mb-3">سياسات شروط {{ $organization->name_ar }}</h5>
                                <p class="text-muted mb-2">{!! is_found($organization->policies) !!}</p>
                                {{-- {!! is_found($organization->about_us) !!} --}}
                            </div>
                        </div>
                        <!--end card-body-->
                    </div><!-- end card -->
                </div>
                <!--end tab-pane-->

                <div class="tab-pane fade" id="activities" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ trans('translation.sectors-activities') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="acitivity-timeline acitivity-main">
                                <div class="acitivity-item d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}"
                                            alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Purchased by James Price</h6>
                                        <p class="text-muted mb-2">Product noise evolve smartwatch </p>
                                        <small class="mb-0 text-muted">05:57 AM Today</small>
                                    </div>
                                </div>
                                <div class="acitivity-item py-3 d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}"
                                            alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Natasha Carey have liked the products</h6>
                                        <p class="text-muted mb-2">Allow users to like products in your WooCommerce store.
                                        </p>
                                        <small class="mb-0 text-muted">25 Dec, 2022</small>
                                    </div>
                                </div>
                                <div class="acitivity-item py-3 d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}"
                                            alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Today offers by <a href="apps-ecommerce-seller-details"
                                                class="link-secondary">Digitech Galaxy</a></h6>
                                        <p class="text-muted mb-2">Offer is valid on orders of $230 Or above for selected
                                            products only.</p>
                                        <small class="mb-0 text-muted">12 Dec, 2022</small>
                                    </div>
                                </div>
                                <div class="acitivity-item py-3 d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}"
                                            alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Flash sale starting <span
                                                class="text-primary">Tomorrow.</span></h6>
                                        <p class="text-muted mb-2">Flash sale by <a href="javascript:void(0);"
                                                class="link-secondary fw-medium">Zoetic Fashion</a></p>
                                        <small class="mb-0 text-muted">22 Oct, 2022</small>
                                    </div>
                                </div>
                                <div class="acitivity-item d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ URL::asset('build/images/users/32/profile-user.png') }}"
                                            alt="" class="avatar-xs rounded-circle acitivity-avatar">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 lh-base">Monthly sales report</h6>
                                        <p class="text-muted mb-2"><span class="text-danger">2 days left</span>
                                            notification to submit the monthly sales report. <a href="javascript:void(0);"
                                                class="link-warning text-decoration-underline">Reports Builder</a></p>
                                        <small class="mb-0 text-muted">15 Oct, 2022</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end card-->

                </div>
                <!--end tab-pane-->
            </div>

        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
