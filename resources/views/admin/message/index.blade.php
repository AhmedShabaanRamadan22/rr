@extends('layouts.master')
@section('title', __('Messages'))
@push('styles')
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <!-- SweetAlert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
    <x-breadcrumb pageTitle="messages" />
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">

                            @component('admin.message.components.new-message', [
                                'senders' => $senders,
                                'users' => $users,
                                'roles' => $roles,
                                'receivers' => $receivers,
                            ])
                            @endcomponent
                            {{-- <x-custom-ckeditor columnName="message"/> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after-scripts')
        <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
        {{-- <script src="{{ URL::asset('build/js/pages/mailbox.init.js') }}"></script> --}}

        <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // $('.selectpicker').selectpicker({
                //     width: '100%',
                // });
            }); //end ready
        </script>
    @endpush
@endsection
