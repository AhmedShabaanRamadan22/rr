<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidableTrait{

    protected static function boot()
	{
		parent::boot();
		static::creating(function ($model) {
			$model->uuid = (string) Str::Uuid();
		});
	}

	// public function getRouteKeyName(): string
	// {
	// 	return 'uuid';
	// }

}