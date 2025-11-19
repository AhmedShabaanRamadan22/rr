@extends('layouts.master')
@section('title', __('Order Type'))

@push('styles')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ trans('translation.nationalities') }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">{{ trans('translation.nationalities') }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">{{ trans('translation.home') }}</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="card card-collapsed">
        <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
            <h3 class="card-title">{{ __('Add New Nationalitiy') }}</h3>
            <div class="card-options">
                <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                        class="fe fe-chevron-up"></i></a>
                <!-- <a href="javascript:void(0)" class="card-options-remove" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a> -->
            </div>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('nationalities.store') }}" method="post">
            @csrf
            <div class=" row ">
                <div class="col-md-3">
                    <div class="row mb-4">
                        <div class="col">
                            <label for="name" class=" form-label">{{ __('Nationalitiy Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="{{ __('Nationalitiy Name') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 my-auto">
                    <button class="btn btn-primary">{{ __('Add') }}</button>
                </div>


            </div>

            </form>
        </div>
    </div>
    <div class="row">
        @foreach ($nationalities as $nationality)
            <div class="col-lg-4 organizarionCard">
                <div class="card">
                    <div class="card-header" title="{{ $nationality->name }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-title">
                                    <p>
                                        {{ $nationality->name }}
                                        <br>
                                        <span><small><a
                                                    href="http://{{ $nationality->domain }}">{{ $nationality->domain }}</a></small></span>
                                    </p>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                    </div>
                    <div class="card-footer">
                        <div class=" d-flex justify-content-end">
                            <button class="btn btn-secondary mx-2 nationality-edit" data-bs-target="#editModal{{$nationality->id}}"
                                data-bs-toggle="modal">Edit</button>
                            <button class="btn btn-danger mx-2" data-nationality-id="{{ $nationality->id }}"
                                data-bs-toggle="modal" data-bs-target="#deleteModal{{$nationality->id}}">Delete</button>

                        </div>
                    </div>
                    {{-- deleteModal --}}
                    <div class="modal fade" id="deleteModal{{$nationality->id}}" tabindex="-1" aria-labelledby="deleteModalLabel"
                        aria-hidden="true">
                        <form action="{{ route('nationalities.destroy', $nationality->id) }}" method="post"
                            id="deleteForm{{ $nationality->id }}">
                            @csrf
                            @method('delete')

                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="deleteModalLabel">
                                            {{ __('nationalities.delete') }}
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="deleteModalBody">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- End deleteModal --}}
                    {{-- editModal --}}
                    <div class="modal fade" id="editModal{{$nationality->id}}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $nationality->id }}" aria-hidden="true" 
                        
                        >
                        <form action="{{ route('nationalities.update', $nationality) }}" method="post"
                            id="editForm{{ $nationality->id }}">
                            @csrf
                            @method('put')
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editModalLabel{{ $nationality->id }}">
                                            {{ __('nationalities.edit') }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="editModalBody">

                                        <label for="name">Nationality Name:</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $nationality->name) }}"
                                            required
                                            
                                            >


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- End editModal --}}
                </div>
            </div>
        @endforeach
    </div>



@endsection


<script>
    const setDeleteable = (nationality) => {
        const body = document.getElementById('deleteModalBody')
        body.innerHTML = '<h1>Hello WORLD JS</h1>'

    }
    setDeleteable()
</script>
