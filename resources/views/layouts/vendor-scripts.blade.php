<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>

<script src="{{ URL::asset('build/libs/datatables/datatables.min.js') }}"></script>


{{-- for excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
{{-- for pdf --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>

<!-- SelectPicker -->
<script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<script src="{{ URL::asset('build/js/datatableLanguage.js') }}">
</script>

<script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script name="sessionMessage">
    // add scrollX attribute to all datatables
    $.extend(true, $.fn.dataTable.defaults, {
        scrollX: true,
    });
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    @if ((session()->has('message')))
        var type = "{{ session()->get('alert-type','info') }}";
        Toast.fire({
            icon: type,
            title: "{{ session()->get('message') }}"
        });
        // switch (type){
        //     case 'info':
        //         toastr.info(" {{ \Session::get('message') }} ");
        //         break;

        //     case 'success':
        //         toastr.success(" {{ \Session::get('message') }} ");
        //         break;

        //     case 'warning':
        //         toastr.warning(" {{ \Session::get('message') }} ");
        //         break;

        //     case 'error':
        //         toastr.error(" {{ \Session::get('message') }} ");
        //         break;
        // }
    @endif
</script>
<script>
    const setLoading = (isLoading)=>{
        const modal = document.getElementById('loading-modal');
        const content = document.getElementById('loading-content');
        if( isLoading){
            document.body.style.overflow = 'hidden';
            content.classList.remove('d-none');
            modal.classList.remove('d-none');
            modal.classList.add('blurLoadingModal');
        }else{
            modal.classList.add('unBlurLoadingModal');
            content.classList.add('d-none');
            setTimeout(() => {
            modal.classList.add('d-none');
            modal.classList.remove('unBlurLoadingModal');
            document.body.style.overflow = 'auto';
            modal.classList.remove('blurLoadingModal');
            }, 500);
        }
    }
    if (document.querySelectorAll("select").length > 0) {
        $.fn.selectpicker.Constructor.DEFAULTS.selectAllText = '{{ trans('translation.select-all') }}';
        $.fn.selectpicker.Constructor.DEFAULTS.deselectAllText = '{{ trans('translation.deselect-all') }}';
        $.fn.selectpicker.Constructor.DEFAULTS.noneResultsText = '{{ trans('translation.No matching records found') }}';
    }
    const datatable_localized = document.documentElement.lang == 'ar' ?
        // '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json' : '';
        {...datatableLang} : '' ;
    const stordeThem = sessionStorage.getItem("data-theme");
    

    window.toolbar = [{
            name: 'clipboard',
            items: ['Undo', 'Redo']
        },
        {
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
        },
        {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',
                'CreateDiv', '-'
            ]
        },
        {
            name: 'links',
            items: ['Link', 'Unlink']
        },
        {
            name: 'colors',
            items: ['TextColor', 'BGColor']
        },
        {
            name: 'tools',
            items: ['Maximize', 'ShowBlocks']
        }
    ];
    window.deleteWarningPopupSetup = {
        title: "{{ trans('translation.Warning') }}",
        text: "{{ trans('translation.Do you really want to delete this?') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.delete') }}',
        confirmButtonColor: '#EE6363',
        cancelButtonText: '{{ trans('translation.cancel') }}',
        cancelButtonColor: '#2c3639',
    };

    window.confirmChangeStatusPopupSetup = {
        title: "{{ trans('translation.warning') }}",
        text: "{{ trans('translation.Do you really want to change status') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.confirm') }}',
        confirmButtonColor: "#CAB272",
        cancelButtonText: '{{ trans('translation.back') }}',
        cancelButtonColor: '#2c3639'
    };

    window.confirmChangeStatusWithNotePopupSetup = {
        title: "{{ trans('translation.warning') }}",
        html: "{{ trans('translation.Do you really want to change status') }}",
        // input: 'text', // Add input field for text input
        // inputPlaceholder: '{{ trans("translation.write-note") }}', // Placeholder for the input field
        html:'{{ trans("translation.Do you really want to change status") }}'+
                '<div class="mt-3 text-start">' +
                '<label for="input-email" class="form-label fs-sm">{{trans("translation.one-note")}}<span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" id="status-note" placeholder="{{trans("translation.write-note")}}">' +
                '</div>',
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans("translation.confirm") }}',
        confirmButtonColor: "#CAB272",
        cancelButtonText: '{{ trans("translation.back") }}',
        cancelButtonColor: '#2c3639',
        preConfirm: () => {
            const note = document.getElementById('status-note').value;
            if (!note) {
                Swal.showValidationMessage('{{ trans("translation.note-is-required") }}');
            }
            return { note: note };
        }
    };
    window.confirmCancelAssistWithNotePopupSetup = {
        title: "{{ trans('translation.warning') }}",
        html: "{{ trans('translation.Do you really want to cancel assist') }}",
        // input: 'text', // Add input field for text input
        // inputPlaceholder: '{{ trans("translation.write-note") }}', // Placeholder for the input field
        html:'{{ trans("translation.Do you really want to cancel assist") }}'+
                '<div class="mt-3 text-start">' +
                '<label for="input-email" class="form-label fs-sm">{{trans("translation.one-note")}}<span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" id="assist-note" placeholder="{{trans("translation.write-note")}}">' +
                '</div>',
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans("translation.confirm") }}',
        confirmButtonColor: "#CAB272",
        cancelButtonText: '{{ trans("translation.back") }}',
        cancelButtonColor: '#2c3639',
        preConfirm: () => {
            const note = document.getElementById('assist-note').value;
            if (!note) {
                Swal.showValidationMessage('{{ trans("translation.note-is-required") }}');
            }
            return { note: note };
        }
    };

    window.confirmUpdatePopupSetup = {
        title: "{{ trans('translation.Warning') }}",
        text: "{{ trans('translation.Do you really want to update') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.confirm') }}',
        confirmButtonColor: '#CAB272',
        cancelButtonText: '{{ trans('translation.back') }}',
        cancelButtonColor: '#2c3639'
    };
    window.confirmSendMessagePopupSetup = {
        title: "{{ trans('translation.Warning') }}",
        text: "{{ trans('translation.Do you really want to Send the message') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.confirm') }}',
        confirmButtonColor: '#CAB272',
        cancelButtonText: '{{ trans('translation.back') }}',
        cancelButtonColor: '#2c3639'
    };
    window.confirmGeneratePopupSetup = {
        title: "{{ trans('translation.info') }}",
        text: "{{ trans('translation.Do you really want to generate the conrtact') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.confirm') }}',
        confirmButtonColor: '#CAB272',
        cancelButtonText: '{{ trans('translation.back') }}',
        cancelButtonColor: '#2c3639'
    };
    window.confirmRecreatePopupSetup = {
        title: "{{ trans('translation.Do you really want to recreate the conrtact') }}",
        text: "{{ trans('translation.this will delete the previous one') }}",
        icon: "warning",
        allowOutsideClick: false,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '{{ trans('translation.confirm') }}',
        confirmButtonColor: '#CAB272',
        cancelButtonText: '{{ trans('translation.back') }}',
        cancelButtonColor: '#2c3639'
    };
    
    function customClearTabSessionStorage(){
        var keysToRemove = ['organization', 'facility', 'order','support','ticket',''];

        // Loop through the list and remove each item from sessionStorage
        keysToRemove.forEach(function(key) {
            sessionStorage.removeItem(key);
        });
    }

    function showMenuTitles() {
        // Select all visible menu items
        document.querySelectorAll(".nav-item").forEach(navItem => {
            if (navItem.offsetParent !== null) { // Check if the element is visible
                let prevTitle = navItem.previousElementSibling;
                while (prevTitle) {
                    if (prevTitle.classList.contains("menu-title")) {
                        prevTitle.classList.remove("d-none");
                        break; // Stop after finding the first `.menu-title`
                    }
                    prevTitle = prevTitle.previousElementSibling;
                }
            }
        });
    }
    showMenuTitles();
    function updateMenuTitlesVisibility() {
        $('.menu-title').each(function () {
            const $title = $(this);
            const $nextItems = $title.nextUntil('.menu-title'); 


            const allHidden = $nextItems.filter(':visible').length === 0;

            if (allHidden) {
                $title.hide(); 
            } else {
                $title.show(); 
            }
        });
    }

    $('#sidebar-menu-search').on('input', function () {
        let searchText = $(this).val().toLowerCase().trim();

        $('.nav-item').each(function () {
            let linkText = $(this).find('.sidebar-label-span').text().toLowerCase();

            if (linkText.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        updateMenuTitlesVisibility(); 
    });

</script>
@yield('script')
@stack('after-scripts')
