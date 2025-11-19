@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

@endpush
<!-- Select2 modal -->
<div class="modal  fade" id="notesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content ">
            <input type="hidden" name="order_id" id="order_id" value="">
            <div class="modal-header">
                <h6 class="modal-title">{{trans('translation.order-notes')}}</h6>
                <button class="btn-close ml-0" data-bs-dismiss="modal" aria-label="Close" type="button">
                    <!-- <span aria-hidden="true">×</span> -->
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="inputNote" class=" form-label">{{trans('translation.note')}}</label>
                        <div class=" row mb-4">
                            <div class="col">
                                <textarea class="form-control summernotes_editor" type="text" name="notes" id="notes" value="" rows="7" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="" class=" form-label">{{trans('translation.note-history')}}</label>
                        <div id="activity-main-div" class="acitivity-timeline acitivity-main" style="overflow-y: scroll;height:50%">

                        </div>
                    </div>
                </div>




            </div>
            <div class="modal-footer">
                <button class="btn ripple btn-success" data-bs-dismiss="modal" id="update_notes">{{trans('translation.update')}}</button>
                <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">{{trans('translation.cancel')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Select2 modal -->

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script>
    $(document).ready(function() {
        // set values when modal shown
        $('#notesModal').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            var order_id = $(e.relatedTarget).attr('data-order-id');

            $('#order_id').val(order_id);

            getOrderNote(order_id);
        });

        $('.summernotes_editor').summernote({
            height: 200
        });


        // submit update
        $('#update_notes').click(function() {
            var notes = $('#notes').val();
            var order_id = $('#order_id').val();

            var data = {
                'order_id': order_id,
                'notes': notes
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ url("admin/orders-notes") }}',
                data: data,
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    Toast.fire({
                        icon: "success",
                        title: "{{trans('translation.Order notes was updated successfuly') }}"
                    });
                    if (xhr.status === 200) {}
                }
            });

        })

        function getOrderNote(order_id) {
            $.ajax({
                type: 'GET',
                url: "{{ url('admin/orders-notes') }}",
                data: {
                    order_id: order_id
                },
                success: function(response) {
                    $('#notes').summernote('code','', {
                        height: 200
                    });
                    $('#activity-main-div').empty();
                    if(response.notes.length > 0){
                        response.notes.forEach(function(note){
                            $('#activity-main-div').append(activityObject(note))
                        })
                    }else {
                        $('#activity-main-div').append($('<div class="text-center">{{trans("translation.no-notes")}}</div>'))

                    }


                }
            });
        }

        function activityObject(note){
            let html =  '<div class="acitivity-item d-flex">'+
                            '<div class="flex-shrink-0">'+
                                '<img src="' + note.user.profile_photo+'" alt="" class="avatar-xs rounded-circle acitivity-avatar">'+
                            '</div>'+
                            '<div class="flex-grow-1 ms-3 pb-4">'+
                                '<h6 class="mb-1 lh-base">Purchased by '+note.user.name+'</h6>'+
                                '<p class="text-muted mb-2">'+note.content+'</p>'+
                                '<small class="text-muted">'+note.since+'</small>'+
                            '</div>'+
                        '</div>';
            return html;
        }
    })
</script>

@endpush
