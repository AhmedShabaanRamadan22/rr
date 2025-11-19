@component('admin.export.pdf')
    @slot('content')
        @include('admin.export.form.sections.form-info')
        <pagebreak />
        @include('admin.export.form.sections.qusetion-section')
    @endslot
@endcomponent
