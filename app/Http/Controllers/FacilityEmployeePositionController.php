<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\EmployeePositionResource;
use Throwable;
use Illuminate\Http\Request;
use App\Models\FacilityEmployeePosition;

class FacilityEmployeePositionController extends Controller
{
    public function index()
    {
        try {
            $positions = FacilityEmployeePosition::all();
            return response(['positions' => EmployeePositionResource::collection($positions)], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
