<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\CityResource;
use Throwable;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(){
        try {
            $cities = City::all();
            return ['cities' => CityResource::collection($cities)];
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
