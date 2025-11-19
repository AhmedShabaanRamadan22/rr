@extends('layouts.master')
@section('title')
@lang('translation.gallery')
@endsection
@push('styles')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" /> -->

<style>


</style>
@endpush
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Gallery</h4>
            </div><!-- end card header -->
            <div class="card-body position-relative">

                <!-- Left Arrow -->
                <div class="swiper-button-prev1" style="position:absolute; top:50%; left:5px; transform:translateY(-50%); z-index:10;">
                    <i class="bi bi-chevron-left fs-3 text-dark"></i>
                </div>

                <!-- Swiper -->
                <div class="swiper responsive-swiper rounded gallery-light p-1 mx-3 bg-body-tertiary">
                    <div class="swiper-wrapper">
                        @foreach($images as $image)
                        <div class="swiper-slide mx-1">
                            <div class="gallery-box card shadow-sm border-0 rounded-3 m-1">
                                <div class="card-body p-2">
                                    <a href="#"
                                        class=" d-block ratio ratio-1x1 bg-body-secondary rounded-3 overflow-hidden"
                                        data-bs-target="#galleryModal"
                                        data-image="{{ $image->url }}"
                                        data-bs-toggle="modal">
                                        <img src="{{ $image->url }}" class="w-100 h-100 object-fit-cover" alt="image">
                                    </a>

                                    <h6 class="mt-2 mb-1 text-truncate">
                                        {{ str_replace('App\\Models\\','',$image->attachmentable->answerable_type ?? "") }}
                                    </h6>
                                    <p class="mb-0 text-muted small">
                                        by <a href="#" class="text-body text-truncate">{{ $image->user->name ?? "" }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Arrow -->
                <div class="swiper-button-next1" style="position:absolute; top:50%; right:5px; transform:translateY(-50%); z-index:10;">
                    <i class="bi bi-chevron-right text-bold fs-3 text-dark"></i>
                </div>

            </div>


            <!-- end card-body -->
        </div><!-- end card -->
    </div><!--end col-->

</div>


<!-- Image Modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="galleryModalImage" src="" class="img-fluid object-fit-contain rounded shadow w-100 h-100" alt="Preview">
            </div>
        </div>
    </div>
</div>

@endsection
@push('after-scripts')

<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/swiper.init.js') }}"></script>

<script>
    var page = 1;
    var loading = false;
    var swiper = new Swiper(".responsive-swiper", {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            prevEl: ".swiper-button-next1",
            nextEl: ".swiper-button-prev1",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 40
            },
            1200: {
                slidesPerView: 4,
                spaceBetween: 50
            },
        },
        on: {
            reachEnd: function() {
                if (!loading) {
                    loading = true;
                    page++;
                    $.ajax({
                        type: "GET",
                        url: "{{ route('admin.api.gallery.index') }}",
                        data: {
                            page: page,
                            per_page: 10
                        },
                        dataType: "json",
                        success: function(res) {
                            if (res.data && res.data.length > 0) {
                                res.data.forEach(img => {
                                    let slide = `
                                        <div class="swiper-slide mx-1">
                                            <div class="gallery-box card shadow-sm border-0 rounded-3 m-1">
                                                <div class="card-body p-2">
                                                    <a href="#"
                                                        class="d-block ratio ratio-1x1 bg-body-secondary rounded-3 overflow-hidden"
                                                        data-bs-target="#galleryModal"
                                                        data-image="${img.url}"
                                                        data-bs-toggle="modal">
                                                        <img src="${img.url}" class="w-100 h-100 object-fit-cover" alt="${img.alt}">
                                                    </a>
                                                    <h6 class="mt-2 mb-1 text-truncate">${img.answerable_type || ''}</h6>
                                                    <p class="mb-0 text-muted small">by 
                                                        <a href="#" class="text-body text-truncate">${img.user_name || ''}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    swiper.appendSlide(slide);
                                });
                                loading = false;
                            }
                        },
                        error: function() {
                            loading = false;
                        }
                    });
                }
            }
        }
    });

    $('#galleryModal').on('show.bs.modal', function(e) {
        //get data-id attribute of the clicked element
        var source = $(e.relatedTarget).attr('data-image');

        $('#galleryModalImage').attr('src', source);

    });
    // document.addEventListener("DOMContentLoaded", function () {
    //     const imageLinks = document.querySelectorAll(".image-popup");
    //     const modalImage = document.getElementById("modalImage");
    //     const imageModal = new bootstrap.Modal(document.getElementById("imageModal"));

    //     imageLinks.forEach(link => {
    //         link.addEventListener("click", function () {
    //             const imgSrc = this.getAttribute("data-image");
    //             modalImage.src = imgSrc;
    //             imageModal.show();
    //         });
    //     });
    // });
</script>

@endpush