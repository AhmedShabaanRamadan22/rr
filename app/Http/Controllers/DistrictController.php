<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\DistrictResource;
use Throwable;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(){
        try {
            $districts = District::all();
            return ['districts' => DistrictResource::collection($districts)];
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
