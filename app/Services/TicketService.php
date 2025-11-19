<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Status;
use Carbon\Carbon;

class TicketService
{

    public static function getTicketGroupedByStatus($tickets, $organization = null){
        $data = [];
        if(isset($organization)){
            $tickets = $tickets->whereHas('order_sector.sector.classification', function($q) use($organization){
                $q->where('organization_id', $organization->id);
            })->get();
        }
        $tickets_group_by_status = $tickets->groupBy('status_id');
        
        foreach(Status::ticket_statuses()->get() as $status){
            if(isset($tickets_group_by_status[$status->id])){
                $data = array_merge($data,[$status->name => count($tickets_group_by_status[$status->id])]);

            }else{
                $data = array_merge($data,[$status->name => 0]);
            }
        }

        return $data;
    } 

    public static function getTicketGroupedByDate($tickets, $dateCategories, $organization = null)
    {
        if(isset($organization)){
            $tickets = $tickets->whereHas('order_sector.sector.classification', function($q) use($organization){
                $q->where('organization_id', $organization->id);
            })->get();
        }
        $tickets_data = $tickets->CountBy(function ($date) {
            return Carbon::parse($date->created_at)->format('m-d-Y'); // grouping by years
        });
        $data = collect($dateCategories)
            ->map(function ($value, $key) use ($tickets_data) {
                return $tickets_data->get($value) ?? 0;
            })->flatten();

        return $data;
    }

    public static function getTicketGroupedByDateAndStatus($tickets, $dateCategories, $organization = null) {
        $data = [];
        if(isset($organization)){
            $tickets = $tickets->whereHas('order_sector.sector.classification', function($q) use($organization){
                $q->where('organization_id', $organization->id);
            })->get();
        }
        $tickets_group_by_status = $tickets->groupBy('status_id');
        foreach(Status::ticket_statuses()->get() as $status){
            if(isset($tickets_group_by_status[$status->id])){
                $tickets_status_data = self::getTicketGroupedByDate($tickets_group_by_status[$status->id], $dateCategories);
                array_push($data,['name'=>$status->name,'data'=>$tickets_status_data]);

            }else{
                array_push($data,['name'=>$status->name,'data'=>array_fill(0,count($dateCategories),0)]);
            }
        }

        return $data;
    }
}
