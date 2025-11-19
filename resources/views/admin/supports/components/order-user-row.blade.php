<!-- User Card -->
<div class="col-md-7 col-sm-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>
                {{__('User Info')}}
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($user->only(['name','email','phone','national_id','birthday']) as $key => $value)
                <div class="col-md-4 col-sm-12 mb-4">
                    <strong>{{__(Str::title(str_replace('_', ' ', $key)))}}</strong>
                    <p>{{$value}}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Order Card -->
<div class="col-md-5 col-sm-12">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>
                {{__('Order Info')}}
            </h3>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-12 mb-4">
                    <strong>{{__('Order ID')}}</strong>
                    <p>{{$order->id}}</p>
                </div>
                <div class="col-md-6 col-sm-12 mb-4">
                    <strong>{{__('Status')}}</strong>
                    <select class="form-control selectpicker status-select" name="order_type_id" style="background:{{$order->status->color}}" data-status-id="{{$order->status_id}}" data-order-id="{{$order->id}}" onchange="changeSelectPicker(this)">
                        @foreach ($statuses as $status)
                        <option value="{{$status->id}}" {{ ($status->id == $order->status->id ? 'selected' : '') }} data-content="<span class='badge ' style='background:{{$status->color}}' >{{$status->name}}</span>">
                            {{$status->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-12 mb-4">
                    <strong>{{__('Order Type')}}</strong>
                    <p>{{$order->organization_service->service->name??"-"}}</p>
                </div>
                <div class="col-md-6 col-sm-12 mb-4">
                    <strong>{{__('Creation Date')}}</strong>
                    <p>{{$order->created_at->format('d/m/Y')}} ({{$order->created_at->diffForHumans()}})</p>
                </div>
            </div>
        </div>
    </div>

</div>