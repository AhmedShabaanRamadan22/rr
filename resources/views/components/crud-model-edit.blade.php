@push('styles')
    <!-- SelectPicker -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <!-- Sweet Alert -->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- <style>
                                            .icon-bigger{
                                                font-size: 22px;
                                            }
                                        </style> -->
@endpush
<x-breadcrumb :pageTitle="$pageTitle" :title="$modelItem->id">
    <li class="breadcrumb-item"><a
            href="{{ route(str_replace('_', '-', $tableName) . '.index') }}">{{ trans('translation.' . str_replace('_', ' ', $tableName)) }}</a>
    </li>

</x-breadcrumb>

<!-- add new question_type -->
<div class="row">
    <div class="col-md-12  col-xl-12">
        <div class="card card-collapsed">
            <div class="card-header" class="card-options-collapse" data-bs-toggle="card-collapse">
                <h3 class="card-title">{{ trans('translation.edit_' . $tableName) }}</h3>
                <div class="card-options">
                    <a href="javascript:void(0)" class="card-options-collapse" data-bs-toggle="card-collapse"><i
                            class="fe fe-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal"
                    action="{{ route(str_replace('_', '-', $tableName) . '.update', $modelItem) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class=" row ">
                        <!-- <div class="col-md-3">
                            <label for="inputName" class=" form-label">{{ trans('translation.question_type_name') }}</label>
                            <input type="text" class="form-control" id="inputName" name="name" placeholder="{{ 'Question-Type Name' }}" required>
                        </div> -->
                        @foreach ($columnInputs as $key => $columnInput)
                            @component('components.inputs.' . $columnInput . '-input', [
                                'columnName' => $key,
                                'columnOptions' => $columnOptions ?? null,
                                'columnSubtextOptions' => $columnSubtextOptions ?? null,
                                'hiddenValue' => $hiddenValue ?? null,
                                'col' => '4',
                                'margin' => 'mb-4',
                                'modelItem' => $modelItem,
                                'name' => $columnInput == 'file' ? 'attachments[' . ($attachmentLabels?->id??0) . ']' : $key,
                                'attachment_label'=>$attachmentLabels??null,
                                'is_required' => $notRequiredColumns[$key] ?? null,
                            ])
                            @endcomponent
                        @endforeach
                        @if (isset($attachment) && isset($attachment->first()['value']))
                            <div class="card-body">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{$attachment->first()['value']}}" title="user-national-id" id="user-national-id" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                        <div class="card-footer">
                            <div class="text-end">
                                <button class="btn btn-secondary px-5 mx-2" type="button" onclick="goBack()"
                                    id="backButton">{{ trans('translation.back') }}</button>
                                <button class="btn btn-primary px-5" disabled type="button"
                                    id="submitButton">{{ trans('translation.update') }}</button>
                            </div>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>


@push('after-scripts')
    <!-- SelectPicker -->
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

    <script>
        function goBack() {
            location.href=localStorage.getItem('goBackHref');
        }
        $(document).ready(function() {
            $(document.body).on('click', '#submitButton', function(e) {
                let submitBtn = $(this);
                Swal.fire(window.confirmUpdatePopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        submitBtn.closest('form').submit();
                    }
                });
            })
            $('.check-empty-input, .form-control').on('change', function(){
                let flag = true;
                let data_regex = $(this).attr("data-regex");
                let column_name = $(this).attr("name");
                if (typeof data_regex !== 'undefined' && data_regex !== false) {
                    var regex = new RegExp(data_regex);
                    if (!regex.test($(this).val())) {
                        $('#error-' + column_name).removeClass('d-none')
                        $('#input_' + column_name).addClass('border-danger')
                        return
                    }
                    $('#error-' + column_name).addClass('d-none')
                    $('#input_' + column_name).removeClass('border-danger')
                }
                $('.check-empty-input').each(function() {
                    if (!$(this).is('div')){
                        if($(this).prop('required') && $(this).val() == ''){
                            flag = false;
                            return;
                        }
                    }
                });
                $('#submitButton').prop('disabled', !flag)
            })

            $('form').on('submit', function(e){
                let sendBtn = $('#submitButton');
                sendBtn.empty();
                sendBtn.append($('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'));
                sendBtn.prop('disabled', true)
            })
        });
    </script>
@endpush
