
@component('modals.add-modal-template',['modalName'=>'categories', 'modalRoute'=>'organization-categories'])
<input type="hidden" name="organization_id" value="{{$organization->id}}" id="hidden_organization_id_category">
<h6>{{trans('translation.categories')}}</h6>
    <select class="form-control selectPicker check-empty-input" name="category_id" id="select_organization_category" title="{{trans('translation.choose-category')}}">
        <!-- <option value="choose_one" disabled selected>{{trans('translation.choose-category')}}</option> -->
        @foreach ($categories as $category)
        <option value="{{$category->id}}">{{$category->name}}</option>
        @endforeach
    </select>
@endcomponent

@push('after-scripts')
<script>
    $(document).ready(function() {
        $('#addcategories').on('show.bs.modal', function(e) {
            var used_categories = $(e.relatedTarget).attr('data-services');
            var category_ids = used_categories.split(',');
            //populate the textbox
            let emptyFlag = true;
            $('#select_organization_category option').each(function() {
                $(this).hide();
                if (!category_ids.includes($(this).val())) {
                    $(this).show();
                    emptyFlag = false;
                }
            });
            if(emptyFlag){
                $('#select_organization_category' ).attr('title','{{trans("translation.no-data")}}');
                $('#select_organization_category').prop('disabled',true);
            }
            $('#select_organization_category').selectpicker('destroy');
            $('#select_organization_category').selectpicker({width: '100%'});
        });
    });
</script>
@endpush
