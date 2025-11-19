<li class="nav-item">
    <a id="{{$id}}-tab" href="#custom-hover-{{$id}}" data-bs-toggle="tab" onclick="setActiveTab('{{$id}}', '{{$parent??null}}')" aria-expanded="false" class="nav-link">
        <i class="{{$icon}} nav-icon nav-tab-position"></i>
        <h5 class="nav-titl nav-tab-position m-0">{{ trans('translation.' . $id) }}</h5>
    </a>
</li>