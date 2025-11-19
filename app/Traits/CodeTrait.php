<?php

namespace App\Traits;

trait CodeTrait{

    public function generateCode($model){
        $arr =[
            'Ticket' => 'TK',
            'Support' => 'SP',
            'Fine' => 'FIN',
        ];


        $order_id = $model->order_sector->order->id;

        $organization_slug = $model->order_sector->sector->classification->organization->slug;

        $model_initial = $arr[class_basename(get_class($model))]. str_pad($model->id, 4, '0', STR_PAD_LEFT);

        $code = $organization_slug .
                '-' .
                $model_initial .
                '-' .
                'OR'.
                str_pad($order_id, 3, '0', STR_PAD_LEFT) ;

        return $code;
    }

}
