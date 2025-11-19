<form class="form-horizontal" action="{{ isset($modalRouteId) ? route($modalRoute, $modalRouteId) : route($modalRoute) }}" method="post" enctype="multipart/form-data">
    @csrf
    @isset($modalRouteMethod)
        @method($modalRouteMethod)
    @endisset
    <div class="modal fade {{$modalSize ?? 'modal-lg'}}" id="{{$modalId}}" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered {{isset($modalMaxHeight) ? 'modal-dialog-scrollable' : ''}}" role="document">
            <div class="modal-content border-0" style="max-height: {{$modalMaxHeight??'auto'}};">
                <div class="modal-header bg-primary p-3">
                    {{$modalHeader}}
                </div>

                {{$modalBody}}

                <div class="border-dashed border-top mx-2 p-2"></div>
                <div class="modal-footer">
                    {{$modalFooter}}
                </div>
            </div>
            <!-- modal-content -->
        </div>
    </div>
</form>
