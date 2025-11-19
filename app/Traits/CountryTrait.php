<?php

namespace App\Traits;

use App\Models\Country;

trait CountryTrait
{

    public function all()
    {

        $countries = Country::all();
        return response(compact('countries'), 200);
    }
}
