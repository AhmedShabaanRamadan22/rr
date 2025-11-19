<div id="notes" class="col-12" contenteditable="false">
    <div class="note">
        {{$model->note->content ?? trans('translation.no-notes')}}
    </div>
</div>
<div id="note-error" class="text-danger d-none">{{trans('translation.note-error')}}</div>
@if($model->notes->count() > 0)
<div type="button"
        class="mt-2 on-default notes-button text-primary"
        data-bs-target="#{{$id}}-note-history"
        data-bs-toggle="modal"
        data-original-title="Show">
            {{trans('translation.all-notes')}}
</div>
@endif
<div class="mt-3">
    <button type="button" id="edit-note" class="btn btn-outline-primary btn-sm" style="">{{trans('translation.add')}} <i class="mdi mdi-file-edit-outline"></i></button>
    <button type="button" id="submit-note" class="btn btn-primary btn-sm d-none">{{trans('translation.save')}} <i class="mdi mdi-check"></i></button>
</div>

@include('components.notes-history', ['id'=>$id, 'model'=>$model])

@push('after-scripts')
    <script>
        // reload the page then fire the toast
        if (localStorage.getItem('reloadPending') != null) {
            let msg = localStorage.getItem('reloadPending');
            localStorage.removeItem('reloadPending');
            // Display the toast after the reload
            Toast.fire({
                icon: "success",
                title: msg
            });
        }
    </script>
    <script>
        let oldNotes = $.trim($('#notes').text());

        var showChar = 350;
        var ellipsestext = "...";
        var moretext = " إظهار المزيد ...";
        var lesstext = " إظهار أقل";

        function checkLength(){
            var contentt = $('#notes').text();
            var content = contentt.replace(/["]+/g,'').substring(1,contentt.length - 1);

            if (content.length > showChar) {
                var c = content.substr(0,showChar);
                var h = content.substr(showChar - 1,content.length- showChar);

                var html = c
                    + '<span class="moreellipses" style="display: none;">'
                    + ellipsestext
                    + '&nbsp;</span><span class="morecontent"><span style="display: none;">'
                    + h
                    + '</span>&nbsp;&nbsp;<a href="" class="morelink less">'
                    + moretext
                    + '</a></span>';

                $('#notes').html(html);
            }
        }

        checkLength()

        $(".morelink").click(function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(lesstext);
            } else {
                $(this).addClass("less");
                $(this).html(moretext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        $('#edit-note').click('click', function(){
            $('#notes').attr('contenteditable', 'true')
            // $('#notes').text(oldNotes == "{{trans('translation.no-notes')}}" ? '' : oldNotes)
            $('#notes').text('')
            $('#submit-note').removeClass('d-none')
            $('#edit-note').addClass('d-none')
            $('#notes').focus()
            $('#notes').addClass('border border-light rounded px-2 py-1')
        });
        $('#submit-note').click('click', function(){
            if(checkNoteChange()){
                $('#notes').attr('contenteditable', 'false')
                $('#edit-note').removeClass('d-none')
                $('#submit-note').addClass('d-none')
                $('#notes').removeClass('border border-light border-danger rounded px-2 py-1')
                $('#note-error').addClass('d-none')
            }
            else{
                $('#notes').removeClass('border-light').addClass('border-danger')
                $('#note-error').removeClass('d-none')
            }
        });

        function checkNoteChange(){
            var new_notes = $.trim($('#notes').text());
            checkLength()
            if(new_notes == ''){
                if(oldNotes == "{{trans('translation.no-notes')}}"){
                    $('#notes').text("{{trans('translation.no-notes')}}")
                    return true
                }
                else{
                    return false
                }
            }
            else{
                if(oldNotes != new_notes){
                    Swal
                        .fire(window.confirmUpdatePopupSetup).then((result) => {
                            if (result.isConfirmed) {
                                setLoading(true);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: "POST",
                                    url: '{{ url("admin/store-notes") }}',
                                    data: {
                                        notes: new_notes,
                                        id: {{$model->id}},
                                        model: '{{$id}}',
                                    },
                                    dataType: "json",
                                    success: function(response, jqXHR, xhr) {
                                        setLoading(false);
                                        oldNotes = new_notes;
                                        localStorage.setItem('reloadPending', "{{trans('translation.Added successfully')}}");
                                        location.reload();
                                    },
                                    error: function(){
                                        setLoading(false);
                                    }
                                });
                            }
                            else{
                                $('#notes').text(oldNotes)
                                checkLength()
                            }
                        });
                }
            }
            return true
        }
    </script>
@endpush
