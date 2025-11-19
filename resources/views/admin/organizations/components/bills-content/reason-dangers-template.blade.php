

<div class="row mt-4">
    @forelse ($organization->reason_dangers->where('operation_type_id', $operation_type->id) as $reason_danger)
        <div class="col-md-6 mb-3 ">
            <div class="card text-center border border-primary my-1 reason-dangerCard">
                <div class="card-body">
                    <div class="d-flex align-items-center row justify-content-between">

                        <div class="col-md-5 d-flex align-items-center mb-3 mb-lg-0">
                           {{ $reason_danger->name }}
                        </div>

                         <!-- Edit Danger Level -->
                        <div class="col-lg-5 col-10">
                            <select class="form-control selectpicker" name="danger_level" data-reason-danger-id="{{ $reason_danger->id }}" onchange="changeDangerLevel(this)">
                                @foreach ($dangers as $danger)
                                    <option value="{{ $danger->id }}" data-bs-theme-mode="" {{ ($danger->id == $reason_danger->danger_id ? 'selected' : '') }}
                                            data-content="<span class='badge {{$danger->color ? "" : "white"}}' style='background-color: {{ $danger->color}}; color:{{$danger->color ? "white" : "black"}};';

                                            >{{ $danger->level }}</span>">
                                        {{ $danger->level }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-md-2 col-2">
                            <form action="{{ route('reason-dangers.destroy', $reason_danger->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete_reason_danger" value="{{ $reason_danger->id }}" data-reason-danger-id="{{ $reason_danger->id }}">
                                    <i class="mdi mdi-trash-can-outline mdi-lg align-middle"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p>{{ trans('translation.no-related-reason-danger') }}</p>
    @endforelse
</div>

@push('after-scripts')
    <!-- Bootstrap SelectPicker Script -->
    <script src="{{ URL::asset('build/libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

    <script type="text/javascript">

            $('.selectpicker').each(function() {
                var selectedOptionColor = $(this).find('option:selected').css('background-color');
                $(this).css('background-color', selectedOptionColor);
            });

            $(document.body).on('click', '.delete_reason_danger', function() {
                let deleteBtn = $(this);
                let model_id = $(this).attr('data-reason-danger-id');
                Swal.fire(window.deleteWarningPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'DELETE',
                            url: "{{ url('/reason-dangers') }}/" + model_id,
                            success: function(response) {
                                deleteBtn.closest('.reason-dangerCard').remove();
                                Toast.fire({
                                    icon: "success",
                                    title: "{{ trans('translation.delete-successfully') }}"
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: "error",
                                    title: "{{trans('translation.you cannot delete this reason-danger there is an operating-type link with it') }}"
                                });
                            },
                        });
                    }
                });
            });

        function changeDangerLevel(element) {
            var reasonDangerId = $(element).data('reason-danger-id');
            var newDangerLevelId = $(element).val();
            var selectedOptionColor = $(element).find('option:selected').css('background-color');
            $(element).css('background-color', selectedOptionColor);

            Swal
                .fire(window.confirmChangeStatusPopupSetup).then((result) => {
                    if (result.isConfirmed) {
                        selected = newDangerLevelId;
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('admin/update-danger-level') }}",
                            type: 'POST',
                            data: {
                                reason_danger_id: reasonDangerId,
                                danger_id: newDangerLevelId,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: "json",
                            success: function(response, jqXHR, xhr) {

                                if (xhr.status === 200) {
                                    Toast.fire({
                                            icon: "success",
                                            title: "{{trans('translation.updated-successfully') }}"
                                        });

                                }
                            }
                        });
                    } else {
                        element.value = selected;
                        select.selectpicker('destroy');
                        select.val(select.attr('data-status-id'));
                        select.selectpicker({
                            width: '100%',
                        });
                    }
                });
        }

    </script>
@endpush


