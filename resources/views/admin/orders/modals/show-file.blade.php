<!-- Select2 modal -->
<div class="modal  fade" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered ">
        <div class="modal-content ">
            
            <input type="hidden" name="order_id" id="order_id" value="">
            <div class="modal-header">
                <h6 class="modal-title">{{__('Show File')}}</h6>
                <button class="btn-close ml-0" data-bs-dismiss="modal" aria-label="Close" type="button">
                    <!-- <span aria-hidden="true">×</span> -->
                </button>
            </div>
            <div class="modal-body">
                <div class=" row mb-4">
                    <div class="col">
                        <embed id="embed-file" src="" >
                        <img id="image-file-show" src="" alt="" class="img-fluid">
                    </div>
                </div>



            </div>
            
        </div>
    </div>
</div>
<!-- End Select2 modal -->

@push('after-scripts')
<script>
    $('#showFile').on('show.bs.modal', function(e) {
        //get data-id attribute of the clicked element
        var src = $(e.relatedTarget).attr('data-src');
        var src_split = src.split('.');
        var type = src_split[src_split.length - 1];
        if(type == 'pdf'){
            var parent = $('embed#embed-file').parent();
            var newElement = "<embed src='"+src+"' id='embed-file' frameborder='0' width='100%' height='400px'>";
    
            $('embed#embed-file').remove();
            parent.append(newElement);

        }else if (type == 'png'){
            $('#image-file-show').attr('src',src);
        }
    });
</script>
@endpush