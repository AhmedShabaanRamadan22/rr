@component('admin.dashboard.admin.components.datatable_models',[
    "model" => ($model = $operations_label['label']),
    "columns"=>$datatable_columns[$model],
    "organization" => $organization,
    "datatableUrl" => route(str_replace('_','-',$model).'.datatable'),
    
])
    
@endcomponent