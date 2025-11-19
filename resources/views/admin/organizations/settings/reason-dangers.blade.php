@extends('admin.organizations.settings.layout.organization-settings')
@section('settings-content')
    @component('components.section-header', ['title' => 'reason-dangers'])@endcomponent
    <div class="tab-pane show active">
        <ul class="nav nav-pills nav-custom-outline nav-primary mb-3" role="tablist">
            @foreach ($operation_types as $operation_type)
            @if(in_array($operation_type->model, ['meals', 'fines']))
            @continue
            @endif
            <li class="nav-item">
                <a class="nav-link {{$loop->index == 0 ? ' active' : ''}}" id="operationTypeTab{{$operation_type->id}}" data-bs-toggle="tab" href="#operation-type-{{$operation_type->id}}" role="tab" onclick="activateOperationType({{$operation_type->id}})">{{$operation_type->name}}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="tab-content text-muted">
        @foreach ($operation_types as $operation_type)
        @if(in_array($operation_type->model, ['meals', 'fines']))
        @continue
        @endif
        <div class="tab-pane {{$loop->index == 0 ? 'active' : ''}}" id="operation-type-{{$operation_type->id}}" role="tabpanel">
            @component('admin.organizations.settings.reason-dangers-template',['organization'=>$organization,'operation_type'=>$operation_type,'dangers'=>$dangers,'reason_danger'=>$operation_type->reason_dangers])@endcomponent
        </div>
        @endforeach
    </div>
@endsection

@section('modals')
    @include('admin.organizations.modals.add-reason-danger')
    
@endsection
