<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\User;
use App\Models\Status;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Traits\AttachmentTrait;
use App\Models\FineOrganization;
use App\Notifications\CrudNotify;
use App\Http\Requests\SectorRequest;
use App\Http\Resources\FineResource;
use App\Traits\LocationTrackerTrait;

class FineController extends Controller
{
    use AttachmentTrait, LocationTrackerTrait;
    public function store(SectorRequest $request)
    {
        //checking if this user is monitor and this user belongs to this sector and provided an order_sector_id

        //must check if this fine belongs to this order sector
        $fine_org = FineOrganization::find($request->fine_organization_id);
        $order_sector = OrderSector::find($request->order_sector_id);
        $user = auth()->user();

        if ($fine_org->organization_id != $order_sector->order->organization_service->organization_id) {
            return response()->json(['message' => trans('translation.Fine doesnt belong to this order sector')], 400);
        }

        $this->attachments_validator($request->all())->validate();

        $fine = Fine::create([
            'fine_organization_id' => $request->fine_organization_id,
            'user_id' => $user->id,
            'status_id' => Status::NEW_FINE,
            'order_sector_id' => request()->order_sector_id,
        ]);

        if ($request->has("notes")) {
            $fine->notes()->create(['content' => $request->notes, 'user_id' => auth()->user()->id]);
        }

        foreach ($request->attachments as $key => $attachment) {
            $this->store_attachment($attachment, $fine, $key, null, $user->id);
        }

        //'device', 'user_id', 'longitude', 'latitude', 'details', 'action'
        // if ($request->has('longitude', 'latitude')) {
            $action = trans('translation.Issue fine: ') . $fine->code;
            $this->tracker($request, $fine, $action);
        // }
        User::find($fine->user->id)->notify(new CrudNotify($fine, 'create'));
        return response()->json(['message' => trans('translation.Fine issued successfully'), 'fine' => new FineResource($fine)], 200);
    }
}
