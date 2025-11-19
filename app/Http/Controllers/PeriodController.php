<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function periods()
    {
        try {
            return response()->json([
                'flag' => true,
                'periods' => Period::all()->unique('name'),
            ], 200);

        } catch (Throwable $th) {
            return response()->json([
                'flag' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
