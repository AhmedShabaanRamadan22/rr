@extends('layouts.master')
@section('title')
@lang('translation.subscriptions')
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
Students
@endslot
@slot('title')
My Subscriptions
@endslot
@endcomponent

                    <div class="row" id="subscriptionList">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center g-3">
                                        <div class="col-xxl-3 col-lg-6 me-auto">
                                            <h6 class="card-title">My Subscriptions</h6>
                                            <p class="text-muted mb-0">Monthly: <b class="text-primary-emphasis">$326.95</b> Yearly: <b class="text-primary-emphasis">$1452.32</b></p>
                                        </div><!--end col-->
                                        <div class="col-xxl-3 col">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" placeholder="Search courses, category price etc...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div><!--end col-->
                                        <div class="col-xxl-auto col-auto">
                                            <a href="apps-learning-list" class="btn btn-primary"><i class="bi bi-plus-circle align-baseline me-1"></i> Add New</a>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div>
                            </div><!--end card-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-custom align-middle table-borderless table-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort cursor-pointer" data-sort="plan">Plan</th>
                                            <th scope="col" class="sort cursor-pointer" data-sort="price">Price</th>
                                            <th scope="col" class="sort cursor-pointer" data-sort="duration">Duration</th>
                                            <th scope="col" class="sort cursor-pointer" data-sort="status">Status</th>
                                            <th scope="col" class="sort cursor-pointer" data-sort="payment_due">Payment Due</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-success-subtle rounded">
                                                                <img src="{{ URL::asset('build/images/learning/shopify.png') }}" alt="" class="avatar-xxs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md mb-2 plan">The Complete Shopify Dropship course</h6>
                                                        <p class="text-muted mb-0">Shopify Development</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">$451.32</td>
                                            <td class="duration">6 Months</td>
                                            <td><span class="badge bg-success-subtle text-success status">Active</span></td>
                                            <td>
                                                <p class="text-danger mb-0 payment_due"><i class="bi bi-clock align-baseline me-1"></i> 2 Days ago</p>
                                            </td>
                                            <td>
                                                <a href="#!" class="text-decoration-underline">Renew Now</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-warning-subtle rounded">
                                                                <img src="{{ URL::asset('build/images/learning/sketch.png') }}" alt="" class="avatar-xxs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md mb-2 plan">UI/UX Style guide With Sketch</h6>
                                                        <p class="text-muted mb-0">Graphic Design</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">$239.99</td>
                                            <td class="duration">9 Months</td>
                                            <td><span class="badge bg-success-subtle text-success status">Active</span></td>
                                            <td>
                                                <p class="text-muted mb-0 payment_due">23 March, 2023</p>
                                            </td>
                                            <td>
                                                <p class="mb-0 text-muted" disabled>Pay Now</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-danger-subtle rounded">
                                                                <img src="{{ URL::asset('build/images/learning/laravel.png') }}" alt="" class="avatar-xxs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md mb-2 plan">Master Laravel for Beginners & Intermediate</h6>
                                                        <p class="text-muted mb-0">Laravel Development</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">$159.27</td>
                                            <td class="duration">1 Years</td>
                                            <td><span class="badge bg-danger-subtle text-danger status">Unactive</span></td>
                                            <td>
                                                <p class="text-muted mb-0 payment_due">15 June, 2022</p>
                                            </td>
                                            <td>
                                                <a href="#!" class="text-decoration-underline mb-0">Pay Now</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-dark-subtle rounded">
                                                                <img src="{{ URL::asset('build/images/learning/flask.png') }}" alt="" class="avatar-xxs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md mb-2 plan">REST APIs With Flask</h6>
                                                        <p class="text-muted mb-0">Flask Development</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">$198.16</td>
                                            <td class="duration">2 Years</td>
                                            <td><span class="badge bg-success-subtle text-success status">Active</span></td>
                                            <td>
                                                <p class="text-muted mb-0 payment_due">19 Feb, 2023</p>
                                            </td>
                                            <td>
                                                <p class="text-muted mb-0">Pay Now</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-danger-subtle rounded">
                                                                <img src="{{ URL::asset('build/images/brands/firefox.png') }}" alt="" class="avatar-xxs">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fs-md mb-2 plan">Conflict Management with Emotional Intelligence</h6>
                                                        <p class="text-muted mb-0">Marketing & Management</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">$324.48</td>
                                            <td class="duration">8 Months</td>
                                            <td><span class="badge bg-danger-subtle text-danger status">Unactive</span></td>
                                            <td>
                                                <p class="text-muted mb-0 payment_due">24 Jan, 2023</p>
                                            </td>
                                            <td>
                                                <a href="#!" class="text-decoration-underline mb-0">Pay Now</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="noresult" style="display: none">
                                    <div class="text-center py-4">
                                        <i class="ph-magnifying-glass fs-1 text-primary"></i>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 164+ courses We did not find any courses for you search.</p>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->

@endsection
@section('script')
<script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/learning-student-subscriptions.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection

