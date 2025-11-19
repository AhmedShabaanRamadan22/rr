@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.500-error')
@endsection

@section('content')
    <section class="auth-page-wrapper py-5 position-relative d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="card mb-0">
                        <div class="row g-0 align-items-center">

                            <!--end col-->
                            <div class="col-xxl-6 mx-auto">
                                <div class="card mb-0 border-0 shadow-none mb-0">
                                    <div class="card-body p-sm-5 m-lg-4">
                                        <div class="error-img text-center px-5">
                                            <img src="{{ URL::asset('build/images/auth/500.png') }}" class="img-fluid"
                                                alt="">
                                        </div>
                                        <div class="mt-4 text-center pt-4">
                                            <div class="position-relative">
                                                <h4 class="fs-2xl error-subtitle text-uppercase mb-0">
                                                    {{ trans('translation.500-message') }}</h4>
                                                <div class="mt-4">
                                                    <a href="{{ url('/') }}" class="btn btn-primary"><i
                                                            class="mdi mdi-home me-1"></i>{{ trans('translation.back-to-home') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>
                            <x-start-journey-card />
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
@endsection
