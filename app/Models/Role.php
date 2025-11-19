<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\RefreshesPermissionCache;

use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;

class Role extends ModelsRole  
{
    
    protected $fillable= ['name','guard_name'];
	// protected $appends = [];
	const SUPERADMIN = 1;
	const ADMIN = 2;
	const ORGANIZATION_EMPLOYEE = 3;
	const ORGANIZATION_ADMIN = 4;
	const EMPLOYEE = 5;
	const MONITOR = 6;
	const SUPERVISOR = 7;
	const BOSS = 8;
	const PROVIDOR = 9;
	const SECTOR_MANAGER = 10;
	const ORGANIZATION_CHAIRMAN = 11;
	const ASSISTANT_REPRESENTER = 12;
	public static function columnNames()
	{
		return array(
			'collapser' => 'collapser',
			'id' => 'id',
			'name' => 'name',
			'guard_name' => 'guard-name',
			'action' => 'action',
		);
	}
	
	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'guard_name' => 'hidden',
			'permissions' => 'multiple-select',
		);
	}

	public static function columnOptions()
	{
		return array(
			'permissions' => Permission::all()->pluck('name', 'id')->toArray(),
		);
	}

	public static function hiddenValue()
	{
		return array(
			'guard_name' => 'web',
		);
	}

    public function linkRelative($request){
        return $this->permissions()->attach($request->permissions);
    }

    
}