<div class="tab-pane fade" id="custom-v-pills-{{$columnName}}" role="tabpanel" aria-labelledby="custom-v-pills-{{$columnName}}-tab">
    <div class="mt-2">
        @component('admin.organizations.components.edit-organization-form')
            {{$slot}}
        @endcomponent
        {{-- <form class="form-horizontal" action="{{ route(($routeForm??'organizations').'.update', $organization->id) }}" method="post"
            enctype="multipart/form-data" onsubmit="formSubmitted()">
            @csrf
            @method('PUT')
            {{$slot}}
        </form> --}}
    </div>
</div>
