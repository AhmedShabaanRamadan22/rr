<?php

namespace App\Traits;


trait LocalizationTrait{

    public function localizeName(){
        return app()->getLocale() == 'en'? $this->name_en : $this->name_ar;
    }
    public function getNameAttribute(){
		return $this->localizeName();		
	}
    public function localize($en, $ar){
        return app()->getLocale() == 'en'? $en : $ar;
    }
}