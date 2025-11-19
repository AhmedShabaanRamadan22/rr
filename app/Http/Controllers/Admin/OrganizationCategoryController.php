<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\OrganizationCategory;


class OrganizationCategoryController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }

    //??=========================================================================================================

    public function checkRelatives($delete_model){
        if($delete_model->forms->isNotEmpty()){
            return trans('translation.delete-forms-first');
        }
        return '';
    }

}
