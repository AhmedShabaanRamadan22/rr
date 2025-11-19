<?php

namespace App\Traits;

use App\Http\Requests\CrudOperationRequest;
use App\Models\Country;
use Illuminate\Http\Request;

trait CrudOperationTrait
{

    use AttachmentTrait;
    protected $model;
    protected $model_name;
    protected $table_name;

    public function set_model($controller)
    {
        $controller_name = explode('\\', $controller);
        $this->model_name = str_replace('Controller', '', end($controller_name));
        $this->model = app('App\Models\\' . $this->model_name);
        $this->table_name = $this->model->getTable();
        // dd($this->model);
    }
    //??=========================================================================================================

    public function index()
    {

        $tableName = $this->table_name;
        $pageTitle = str_replace('_', ' ', ucwords($tableName, '_'));
        $can_all_columns = $this->all_columns??null;
        $columns = $this->model::columnNames($can_all_columns);
        $columnInputs = $this->model::columnInputs();
        $columnOptions = null;
        $columnSubtextOptions = null;
        $hiddenValue = null;
        $filterColumns = null;
        $attachmentLabels = null;
        $notRequiredColumns = null;
        
        if(method_exists($this->model, 'columnOptions')){
            $columnOptions = $this->model::columnOptions();
        }
        if(method_exists($this, 'getAttachmentLabels')){
            $attachmentLabels = $this->getAttachmentLabels($tableName);
        }
        if(method_exists($this->model, 'columnSubtextOptions')){
            $columnSubtextOptions = $this->model::columnSubtextOptions();
        }
        if(method_exists($this->model, 'hiddenValue')){
            $hiddenValue = $this->model::hiddenValue();
        }
        if(method_exists($this->model, 'notRequiredColumns')){
            $notRequiredColumns = $this->model::notRequiredColumns();
        }
        if(method_exists($this->model, 'filterColumns')){
            $filterColumns = $this->model::filterColumns();
        }
        // dd($columnInputs,$columnOptions);
        return view('admin.' . $this->table_name . '.index', compact('columns', 'tableName','columnInputs','columnOptions','columnSubtextOptions','pageTitle','hiddenValue', 'filterColumns','can_all_columns', 'attachmentLabels','notRequiredColumns'));
    }

    //??=========================================================================================================

    public function store(CrudOperationRequest $request)
    {
        if(method_exists($this, 'custom_validates')){
            if( ( $message = $this->custom_validates($request) ) != null){
                return back()->with(['message'=> $message,'alert-type'=>'error']);
            }
        }
        $new_model = $this->model::create($request->only($this->model->getFillable()));
        if($request->has('attachments')){
            foreach ($request->attachments as $key => $attachment) {
                $this->update_attachment($attachment, $new_model, $key, null, auth()->user()->id);
            }
        }
        if($request->has('input_custom_files')){
            foreach ($request->input_custom_files as $key_id => $input_custom_file) {
                $this->store_attachment($input_custom_file, $new_model, $key_id, null, auth()->user()->id);
            }
        }
        
        if(method_exists($new_model, 'linkRelative')){
            $linkRelative = $new_model->linkRelative($request);
        }
        return back()->with(['message'=> trans('translation.Added successfully'),'alert-type'=>'success']);
    }

    //??=========================================================================================================

    public function edit($id)
    {
        $modelItem = $this->model::find($id);

        $tableName = $this->table_name;
        $pageTitle = str_replace('_', ' ', ucwords($tableName, '_'));
        $columnInputs = $this->model::columnInputs();
        $columnSubtextOptions = null;
        $columnOptions = null;
        $hiddenValue = null;
        $columnOptionParameters = null;
        $attachmentLabels = null;
        $notRequiredColumns = null;

        if(method_exists($this, 'getColumnOptionParameters')){
            $columnOptionParameters = $this->getColumnOptionParameters($modelItem);
        }
        if(method_exists($this, 'getAttachmentLabels')){
            $attachmentLabels = $this->getAttachmentLabels($modelItem);
        }
        if(method_exists($this->model, 'columnOptions')){
            $columnOptions = $this->model::columnOptions($columnOptionParameters);
        }
        if(method_exists($this->model, 'columnSubtextOptions')){
            $columnSubtextOptions = $this->model::columnSubtextOptions();
        }
        if(method_exists($this->model, 'notRequiredColumns')){
            $notRequiredColumns = $this->model::notRequiredColumns();
        }
        if(method_exists($this->model, 'hiddenValue')){
            $hiddenValue = $this->model::hiddenValue();
        }
        return view('admin.' . $this->table_name . '.edit', compact('tableName','columnInputs','columnOptions','columnSubtextOptions','pageTitle','hiddenValue','modelItem','attachmentLabels','notRequiredColumns'));

    }

    //??=========================================================================================================

    public function update(Request $request,$id)
    {
        $model_item = $this->model::find($id);
        $new_model = $model_item->update($request->only($this->model->getFillable()));
        if($request->hasFile("attachments")){
            foreach ($request->attachments as $key => $attachment) {
                $this->update_attachment($attachment, $model_item, $key, null, auth()->user()->id);
            }
        }
        if(method_exists($model_item, 'linkRelative')){
            $linkRelative = $model_item->linkRelative($request);
        }
        if(method_exists($this, 'return_update_response')){
            return $this->return_update_response();
        }

        return back()->with('message', trans('translation.Updated successfully'));
    }


    //??=========================================================================================================

    public function destroy(string $id)
    {
        $delete_model = $this->model::findOrFail($id);
        if(method_exists($this, 'checkRelatives')){
            if(( $message =  $this->checkRelatives($delete_model) ) != ''){
                return response(array('message' => $message, 'alert-type' => 'error'), 400);
            }
        }
        $delete_model->delete();

        if(method_exists($this, 'return_destroy_back')){
            return $this->return_destroy_back();
        }
        return response(array('message' => trans("translation.Deleted successfully"), 'alert-type' => 'success'), 200);
    }

    //??=========================================================================================================
}
