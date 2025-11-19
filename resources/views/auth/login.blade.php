@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signin')
@endsection
@section('content')
    <section class="auth-page-wrapper py-5 position-relative d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="card mb-0">
                        <div class="row g-0 align-items-center">
                            <div class="col-xxl-6 mx-auto">
                                <div class="card mb-0 border-0 shadow-none mb-0">
                                    <div class="card-body p-sm-5 m-lg-4">
                                        <div class="text-center mt-5">
                                            <h5 class="fs-3xl">{{ trans('translation.welcome-back') }}</h5>
                                            <p class="text-muted">{{ trans('translation.welcome-msg') }}</p>
                                        </div>
                                        <div class="p-2 mt-5">
                                            <form action="{{ route('login') }}" method="post">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="email"
                                                        class="form-label text-right">{{ trans('translation.email') }}
                                                        <span class="text-danger"> * </span></label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email') }}" id="email"
                                                        name="email" placeholder="example@example.com">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    {{-- <div class="float-end">
                                                        <a href="{{ route('password.update') }}"
                                                            class="text-muted">{{ trans('translation.forgot-password') }}</a>
                                                    </div> --}}
                                                    <label class="form-label"
                                                        for="password-input">{{ trans('translation.password') }}<span
                                                            class="text-danger"> * </span></label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password"
                                                            class="form-control password-input pe-5 @error('password') is-invalid @enderror"
                                                            id="password-input" name="password" placeholder="******"
                                                            value="">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                            type="button" id="password-addon"><i
                                                                class="ri-eye-fill align-middle"></i></button>
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" value="1"
                                                        id="auth-remember-check">
                                                    <label class="form-check-label"
                                                        for="auth-remember-check">{{ trans('translation.remember-me') }}</label>
                                                </div>
                                                <div class="mt-4">
                                                    {!! NoCaptcha::display() !!}
                                                    @error('g-recaptcha-response')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mt-4">
                                                    <button class="btn btn-primary w-100"
                                                        type="submit">{{ trans('translation.signin') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-start-journey-card />
                            <!--end col-->
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
@section('script')
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/swiper.init.js') }}"></script>
    {!! NoCaptcha::renderJs() !!}

@endsection
