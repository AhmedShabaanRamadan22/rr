<div class="d-flex py-3 border-bottom border-light">
    <label for="{{$id}}" class="fw-semibold {{$label_col ?? 'col-4'}}">{{$label}}</label>
    <div id="{{$id}}" class="{{$content_col ?? 'col-8'}}">{{$slot}}</div>
</div>
