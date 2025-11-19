<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSenderRequest;
use App\Http\Requests\UpdateSenderRequest;
use App\Models\Sender;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class SenderController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'organization:id,name_ar,name_en'
        )->select('id','name', 'whatsapp_instance_id', 'whatsapp_token', 'email', 'phone_app_sid', 'phone_sender_id', 'operation_support_phone','send_sms')->get();
        // for($i=1;$i<3000;$i++){Sender::create(['name'=> 'test', 'whatsapp_instance_id'=> 'test_id', 'whatsapp_token'=> 'testToker', 'email'=> 'test@test.com', 'phone_app_sid'=> 'xleJC7SDnWQb', 'phone_sender_id'=> 'ReemTest','send_sms'=> '0','operation_support_phone' => '557438555']);}
        return datatables($query)
            ->editColumn('organization_name', function ($row) {
                return $row->organization->name ?? trans("translation.no-selected-organization");
            })
            ->editColumn('send_sms_icon', function ($row) {
                return '<i class="' . ($row->able_to_send_sms ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' "></i>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletesenders" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['color', 'action', 'send_sms_icon'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->organization) {
            return trans('translation.related-organization');
        }
        return '';
    }
}
