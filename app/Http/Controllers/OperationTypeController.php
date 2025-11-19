<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperationTypeResource;
use App\Models\Period;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Models\OperationType;
use App\Models\AttachmentLabel;
use App\Models\FineOrganization;

class OperationTypeController extends Controller
{
    public function index(){

        $order_sector = OrderSector::find(request()->order_sector_id);
        if(!$order_sector){
            return response()->json(['message' => trans('translation.No order sector provided')], 401);
        }
        
        if($order_sector->order->service->name_en != "Catering"){
            $operations = OperationType::whereNotIn('model',['meals','supports']);//->get();
        }

        $operations = OperationType::whereNotIn('model',['meals', 'fines']);//->get();
        $operations = $operations->with(['reason_dangers' => function ($query) use ($order_sector){
            $query->where('organization_id', $order_sector->sector->classification->organization->id)
                  ->with('danger');
        }]);
        return response()->json(['operations' => OperationTypeResource::collection($operations->get())],200);
    }

    public function chooseOperation(Request $request){

        $order_sector = OrderSector::find($request->order_sector_id);
        $periods = Period::where('operation_type_id', $request->operation_id)->get(); 

        $operation = OperationType::with(['reason_dangers' => function ($query) use ($order_sector){
            $query->where('organization_id', $order_sector->sector->classification->organization->id)
                  ->with('danger');
        }])->where('id', $request->operation_id)->get();

        return response()->json([
                        // 'periods'=> $periods,//?? [],
                        'operation'=> new OperationTypeResource($operation->first()),
                        // 'attachment_labels'=> AttachmentLabel::where('type', $operation->first()->model)->get(),
                    ], 200);
    }
}
