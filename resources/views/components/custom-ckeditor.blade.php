<label for="editor{{ $columnName }}" class="form-label">{{ trans('translation.' . (str_replace('_','-',$columnName))) }}</label>
<textarea name="{{ str_replace('-', '_', $columnName) }}" id="editor{{ $columnName }}" cols="{{ $colsValue ?? '30' }}"
    rows="{{ $rowsValue ?? '10' }}" value="" placeholder="{{ trans('translation.write-here') }}">{{ $slot }}</textarea>

@push('after-scripts')
    {{-- rakaya.team@gmail.com --}}
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
    {{-- <script src="https://cdn.tiny.cloud/1/r42uk30rtmhtm3u9le6cfk10idwfvdlxgrsv6n84hxle4yte/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script> --}}
    <script src="https://cdn.tiny.cloud/1/g7ykwo4tcovpane2esf8vfx6ayrrh28893l8xhqf3rwehp8n/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'autolink lists link image charmap print preview pagebreak table',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | pagebreak | table',
            height: 400,
            language: 'ar',
            directionality: 'rtl',
            content_style: 'body { font-family: "IBM Plex Sans Arabic", sans-serif; color: #333; background-color: #f8f8f8; }',
            setup:function(ed) {
                ed.on('change', function(e) {
                });
            }
            
        });
    </script>
@endpush
