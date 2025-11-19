<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\TrackLocation;



trait LocationTrackerTrait{

    public function tracker(Request $request, $model, $action = null){
            $location = $model->track_locations()->create([
                'device'=> $request->device?? null,
                'user_id'=> $request->user()->id,
                'longitude'=> $request->longitude??0, 
                'latitude'=> $request->latitude??0, 
                'details'=> $request->details?? null, 
                'action'=> $action?? null,
                'device_info' => json_decode($request->device_info, true)??null,
            ]);  
            return $location;   
    }

}