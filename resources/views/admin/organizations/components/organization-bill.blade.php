<a class="nav-link" id="custom-v-pills-{{$columnName}}-tab" data-bs-toggle="tab" href="#custom-v-pills-{{$columnName}}" role="tab"
    aria-controls="custom-v-pills-{{$columnName}}" aria-selected="false" onclick="setActiveTab('{{$columnName}}','{{$parent}}')">
    <i class="{{$billIcon}} d-block fs-3xl mb-1"></i> {{ trans('translation.'.$columnName) }}
</a>
