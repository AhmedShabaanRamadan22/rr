<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CrudOperationRequest;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\StageBank;

class StageBankController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function store(CrudOperationRequest $request)
    {
        $this->model::create([
            'name' => $request->name,
            'duration' => $request->duration,
            'arrangement' => $this->model::get()->count() + 1,
        ]);

        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function destroy(StageBank $stageBank)
    {

        if (method_exists($this, 'checkRelatives')) {
            if (($message =  $this->checkRelatives($stageBank)) != '') {
                return response(array('message' => $message, 'alert-type' => 'error'), 400);
            }
        }
        $stageBank->delete();

        //TODO check the logic of re-arrange
        $stages = $this->model::orderBy('arrangement')->get();
        foreach ($stages as $key => $stage) {
            $stage->update([
                'arrangement' => $key + 1
            ]);
        }

        return response()->json(['message' => trans('translation.Deleted successfully')], 200);
    }
    //??=========================================================================================================
    public function sort(Request $request)
    {
        // dd($request->all());
        $items = explode(',', $request->items);
        foreach ($items as $key => $item) {
            $stage = $this->model::find($item);
            $stage->update([
                'arrangement' => $key + 1
            ]);
        }

        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select('id', 'name', 'duration', 'arrangement', 'description')->orderBy('arrangement');
        // for($i=1;$i<3000;$i++){StageBank::create(['name'=>'test6','duration'=>'15','description'=>'not good performance:)))','arrangement' => '6']);}

        return datatables($query->get())
        ->addColumn('action', function ( $row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete' . $this->table_name . '" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color', 'action'])

            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->organization_stages->isNotEmpty()) {
            return trans('translation.delete-organization_stages-first');
        }
        return '';
    }
}
