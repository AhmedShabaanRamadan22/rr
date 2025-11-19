<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
    @foreach($order->group_order_type->sections as $section)
    <li class="nav-item"><a class="nav-link {{ $loop->first ?  'active show':''}}" data-bs-toggle="tab" href="#section-{{$section->id}}" role="tab">{{$section->name}}</a></li>
    @endforeach
    <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Profile">Profile</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#Contact">Contact</a></li> -->
</ul>
<div class="tab-content p-3 text-muted">
    @foreach($order->group_order_type->sections as $section)
    <div class="tab-pane {{ $loop->first ?  'active show':''}}" id="section-{{$section->id}}"  role="tabpanel">
        <div class="row">
            @foreach ($section->questions as $question)
            <div class="col-md-6 col-lg-6 col-sm-12">
                <strong>{{$question->content}}</strong>
                @if($question->type == "File" && !is_null($question->user_answer($order->id)))
                <div class="mt-2">
                    <button class="btn btn-primary " data-bs-target="#showFile" data-bs-toggle="modal" data-src="{{($question->user_answer_file($order->id))}}">{{$question->user_answer($order->id)->value}}</button>
                </div>
                @elseif(in_array($question->type,['Radio', 'Select', 'Multiple_Select', 'Checkbox']) && $question->user_answer_option($order->id))
                <p>{{$question->user_answer_option($order->id)->pluck('content')->implode(',')}}</p>
                @else
                <p>{{$question->user_answer($order->id)->value??'-'}}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    @endforeach
</div>

@include('admin.orders.modals.show-file')
