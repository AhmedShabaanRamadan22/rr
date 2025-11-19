<div class="col-xxl-5">
    <div class="card auth-card bg-secondary h-100 border-0 shadow-none d-none d-sm-block mb-0">
        <div class="card-body py-5 d-flex justify-content-between flex-column">
            <div class="text-center">
                <h3 class="text-white">{{ trans('translation.slugin-1') }}</h3>
                <p class="text-white opacity-75 fs-base">{{ trans('translation.slugin-2') }}
                </p>
            </div>

            <div
                class="auth-effect-main my-5 position-relative rounded-circle d-flex align-items-center justify-content-center mx-auto">
                <div
                    class="effect-circle-1 position-relative mx-auto rounded-circle d-flex align-items-center justify-content-center">
                    <div
                        class="effect-circle-2 position-relative mx-auto rounded-circle d-flex align-items-center justify-content-center">
                        <div
                            class="effect-circle-3 mx-auto rounded-circle position-relative text-white fs-4xl d-flex align-items-center justify-content-center">
                            <span class="text-primary me-1 ms-1">{{ trans('translation.all') }}</span>
                            <span> {{ trans('translation.you-want') }}
                            </span>
                        </div>
                    </div>
                </div>
                <ul class="auth-user-list list-unstyled">
                    <li>
                        <div class="avatar-sm d-inline-block">
                            <div class="avatar-title bg-white shadow-lg overflow-hidden rounded-circle">
                                <img src="{{ URL::asset('build/images/tools/1.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="avatar-sm d-inline-block">
                            <div class="avatar-title bg-white shadow-lg overflow-hidden rounded-circle">
                                <img src="{{ URL::asset('build/images/tools/2.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="avatar-sm d-inline-block">
                            <div class="avatar-title bg-white shadow-lg overflow-hidden rounded-circle">
                                <img src="{{ URL::asset('build/images/tools/3.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="avatar-sm d-inline-block">
                            <div class="avatar-title bg-white shadow-lg overflow-hidden rounded-circle">
                                <img src="{{ URL::asset('build/images/tools/4.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="avatar-sm d-inline-block">
                            <div class="avatar-title bg-white shadow-lg overflow-hidden rounded-circle">
                                <img src="{{ URL::asset('build/images/tools/5.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text-center">
                <a target="_blank" class="text-white opacity-75 mb-0 mt-3">
                    {{ trans('translation.copyright') }}
                    <i class="mdi mdi-heart text-primary"> </i>
                    {{ trans('translation.company') }}
                    &copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script>
                </a>
            </div>
        </div>
    </div>
</div>
