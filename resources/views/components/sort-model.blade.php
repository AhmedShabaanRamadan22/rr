{{-- @dd($stages) --}}

    {{-- <x-data-table id="{{ str_replace('_', '-', $tableName) }}-sort-datatable" :columns="$columns" /> --}}
    <div class="row">
        <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ trans('translation.'.$modalName) }}</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="list-group col nested-list nested-sortable" id="sortable">
                        {{-- {{ $questions->sortable_name }} --}}
                    </div>
                    <small class="text-info my-2">{{trans('translation.drag-and-drop')}}</small>
                </div><!-- end card-body -->
        </div>
    </div>
@push('after-scripts')

    <!-- Sortable -->
    <script src="{{ URL::asset('build/libs/sortablejs/Sortable.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/nestable.init.js') }}"></script>
    <script>
        $(document).on('shown.bs.modal',function() {
            $.ajax({
                type: "GET",
                url: "{{ route($modalRoute) }}",
                data: {
                    @if (isset($questions))
                    question_id: {{$questionableId}},
                    question_type: '{{$questionableType}}',
                    @endif
                    @if (isset($organization))
                        organization_id: {{ $organization->id }}
                    @endif
                },
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    let data= response.data
                    $('#sortable').empty();
                    console.log(data);
                    if (data.length > 1) {
                        let sendBtn = $('#submit-sort-' + '{{$modalName}}' + '-btn');
                        sendBtn.prop('disabled', false)
                    }

                    data.forEach(element => {
                            $('#sortable').append(
                                `<div class="list-group-item nested-1" data-id="`+element.id+`">`+element.sortable_name+`</div>`
                            )
                        });

                },
                error: function(jqXHR, responseJSON) {
                },
            });
        })
    </script>

@endpush
