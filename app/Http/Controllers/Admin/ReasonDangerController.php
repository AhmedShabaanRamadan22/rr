<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ReasonDanger;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class ReasonDangerController extends Controller
{

    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function levelUpdate(Request $request)
    {
        $reason_danger = ReasonDanger::find($request->reason_danger_id);
        if(!$reason_danger)
            return response(['message' => 'reason danger not found'], 404);

        $reason_danger->danger_id = $request->input('danger_id');
        $reason_danger->save();

        return response(['message' => 'Danger level updated successfully'],200);
    }
    //??=========================================================================================================
    public function destroy(ReasonDanger $reasonDanger)
    {
        $reasonDanger->delete();
        return response()->json(['message' => 'Reason danger deleted successfully'],200);

    }
}
