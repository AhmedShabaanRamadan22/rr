@forelse ($operations_label['charts'] as $key => $operation_chart)
    @include('admin.dashboard.admin.sections.' . $operations_label['label'] . '.' . $operation_chart)
@empty

@endforelse