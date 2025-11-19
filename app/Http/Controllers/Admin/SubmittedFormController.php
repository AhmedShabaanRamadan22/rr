<?php

namespace App\Http\Controllers\Admin;

use Alkoumi\LaravelHijriDate\Hijri;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Services\AnswerService;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\SubmittedForm;
use App\Services\HandoverProviderAnswerService;
use App\Traits\PdfTrait;
use Carbon\Carbon;

class SubmittedFormController extends Controller
{
    use PdfTrait, CrudOperationTrait;

    protected $data;
    protected $view_path = 'admin.export.';
    protected $orientation = "P";
    protected $minaFormsArray = [56,62,61,59];
    protected $arafahFormsArray = [64,63,58,60];
    // protected $minaArafahFormsArray = $arafahFormsArray + $minaFormsArray;

    public function __construct()
    {
        $this->set_model($this::class);
        $this->data = array(
            'current_year' => date('Y'),
            'current_date' => date('Y-m-d H:i:s'),
            'attachment_label' => 'تقرير ',
            'header_default_logo' => 'https://rakaya.sa/_next/static/media/Gold2.22225b5d.webp'
        );
    }

    public function storeUserId(Request $request, $user_id)
    {
        session()->forget('submitted_forms_order_sector_id');
        $request->session()->flash('submitted_forms_user_id', $user_id);
        return redirect()->route('submitted-forms.index');
    }

    public function storeOrderSectorId(Request $request, $order_sector_id)
    {
        session()->forget('submitted_forms_user_id');
        $request->session()->flash('submitted_forms_order_sector_id', $order_sector_id);
        return redirect()->route('submitted-forms.index');
    }

    public function all_answers($submitted_form)
    {
        return $submitted_form->load(['form.sections_has_question.visible_questions.answers' => function ($q) use ($submitted_form) {
            $q->where([['answerable_id', $submitted_form->id], ['answerable_type', 'App\Models\SubmittedForm']]);
        }]);
    }

    public function pdfGovReport($submitted_form_uuid, $output = "I")
    {
        $this->generatePdfReport($submitted_form_uuid, true , $output);
    }

    public function pdfReport($submitted_form_uuid, $output = "I")
    {
        $this->generatePdfReport($submitted_form_uuid, false , $output);
    }

    public function generatePdfReport($submitted_form_uuid, $for_gov, $output = "I")
    {
        $submitted_form = SubmittedForm::withTrashed()->where('uuid', $submitted_form_uuid)->firstOrFail();
        $namePdf = $submitted_form->form->name . ' - ' . $submitted_form->order_sector->sector->label .' - '. $submitted_form->order_sector->order->facility->name .' - ' .$submitted_form->form->organization_category->category->name.' - '. $submitted_form->form->organization_service->organization->name . ' - ' . Carbon::now();
        $minaForms = $this->minaFormsArray;
        $arafahForms = $this->arafahFormsArray;
        $minaFormFlag = (in_array($submitted_form->form->id ,$minaForms ));
        $arafahFormFlag = (in_array($submitted_form->form->id ,$arafahForms ));

        if( $for_gov && ($minaFormFlag || $arafahFormFlag) ){

            $answers = Answer::where([['answerable_id', $submitted_form->id], ['answerable_type', 'App\Models\SubmittedForm']])->get();
            $handover_provider_answer_service = new HandoverProviderAnswerService();
            $hijri = new Hijri();
            $this->setPdfData([
                'sector' => $submitted_form->order_sector->sector,
                'nationality_name' => $submitted_form->order_sector->sector->nationality_organization->nationality->name,
                'organization_name' => $submitted_form->order_sector->sector->nationality_organization->organization->name,
                'organization_license_id' => $submitted_form->order_sector->sector->nationality_organization->organization->license_id??null,
                'facility' => $submitted_form->order_sector->order->facility,
                "answers" => $answers,
                "submitted_form" => $submitted_form,
                "handover_provider_answer_service" => $handover_provider_answer_service,
                "minaFormFlag" => $minaFormFlag  ? "checked":"unchecked",
                "arafahFormFlag" => $arafahFormFlag  ? "checked":"unchecked",
                "hijri" =>$hijri,
            ]);

            $mpdf = $this->mPdfInit('submitted_forms.handover_providers_submitted_forms',"","");
        }else {

            $answer_service = new AnswerService();
            $this->setPdfData([
                'attachment_label' => 'تقرير عن الاستمارة المسلمة',
                'organization_data' => $submitted_form->form->organization_service->organization,
                'form_data' => $submitted_form->form,
                'submitted_form_data' => $submitted_form,
                'body_content' => $this->all_answers($submitted_form),
                'sector' => $submitted_form->order_sector->sector,
                'facility' => $submitted_form->order_sector->order->facility,
                'answer_service' => $answer_service,
            ]);
            $mpdf = $this->mPdfInit('submitted_forms.submitted_forms');
        }
        return $mpdf->Output($namePdf . '.pdf', $output);
    }

    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'order_sector.order.organization_service.service:id,name_ar,name_en',
            'order_sector.order.facility:id,name',
            'order_sector.order.organization_service.organization:id,name_ar,name_en',
            'order_sector.sector:id,label,nationality_organization_id',
            'order_sector.sector.nationality_organization.nationality:id,name',
            'order_sector.monitor_order_sectors.monitor.user:id,name',
            'form:id,name,organization_category_id',
            'form.sections_has_question:id,name,is_visible,form_id',
            'form.organization_category:id,category_id',
            'form.organization_category.category:id,name',
            // 'user:id,name',
            'submitted_section:id,section_id,user_id,submitted_form_id',
            'submitted_section.user:id,name',
        );

        if (\request('order_sector_id')) {
            $query->whereIn('order_sector_id', $request->order_sector_id);
        }
        if (\request('category_id')) {
            $query->whereHas('form.organization_category', function ($q) {
                $q->whereIn('category_id', \request('category_id'));
            });
        }
        if (\request('user_id')) {
            $query->whereIn('user_id', $request->user_id);
        }
        if (\request('form_id')) {
            $query->whereIn('form_id', $request->form_id);
        }
        if(\request('organization_id')){
            $query->whereHas('form.organization_category', function ($q) {
                $q->whereIn('organization_id', \request('organization_id'));
            });
        }

        if(\request('sector_nationality_id')){
            $query->whereHas('order_sector.sector.nationality_organization.nationality', function ($q) {
                $q->whereIn('id', \request('sector_nationality_id'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $search = $search['value'];
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('order_sector.monitor_order_sectors.monitor.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('submitted_section.user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $query->orderByDesc('updated_at');

        if(request()->input('isPaginated', false))
        {
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $page = ($start / $length) + 1;

            $paginatedQuery = $query->paginate($length, ['*'], 'page', $page);
            $finalQuery = $paginatedQuery->getCollection();
            $recordsTotal = $paginatedQuery->total();
            $recordsFiltered = $paginatedQuery->total();
        }
        else{
            $finalQuery = $query->get();
            $recordsTotal = $query->count();
            $recordsFiltered = $query->count();
        }

        $transformedData = $finalQuery->map(function ($submittedForm) {
            return [
                'created_at' => isset($submittedForm->created_at) ? $submittedForm->created_at . ' (' . $submittedForm->created_at->diffForHumans() . ')' : '',
                'updated_at' => isset($submittedForm->updated_at) ? $submittedForm->updated_at . ' (' . $submittedForm->updated_at->diffForHumans() . ')' : '',
                'form' => $submittedForm->form->name,
                'organization_id' => $submittedForm->order_sector->order->organization_service->organization->id ?? '-',
                'order_sector_name' => $submittedForm->order_sector->order_sector_name,
                'category' => $submittedForm->form->organization_category->category->name,
                'monitors' => $submittedForm->order_sector->monitors_name ?? '-',
                'sector_nationality' => $submittedForm->order_sector->sector->nationality_organization->nationality->name ?? '-',
                'submitted_section' => $submittedForm->submitted_section->user->name ?? '-',
                'is_completed' => $submittedForm->is_completed ? trans('translation.yes') : trans('translation.no'),
                'sector-reports' => '<div class="flex-row">
                                        <a
                                        class="btn btn-outline-primary btn-sm m-1 on-default "
                                        href="' . (route('admin.order-details.submitted-form-report', [$submittedForm->id])) . '"
                                        target="_blank"
                                        ><i class="mdi mdi-file-document-outline"></i>
                                    </a>
                                    <a target="_blank"
                                        class="btn btn-outline-success btn-sm m-1 on-default "
                                        href="' . (route('admin.order-details.submitted-form-report', [$submittedForm->id, 'D'])) . '"
                                        ><i class="mdi mdi-download-outline"></i>
                                    </a>
                                   </div>',
                'monitor-reports' => '<div class="flex-row">
                                            <a
                                            class="btn btn-outline-primary btn-sm m-1 on-default "
                                            href="' . (route('admin.monitors.submitted-forms-report', [$submittedForm->id])) . '"
                                            target="_blank"
                                            ><i class="mdi mdi-file-document-outline"></i>
                                        </a>
                                        <a target="_blank"
                                            class="btn btn-outline-success btn-sm m-1 on-default "
                                            href="' . (route('admin.monitors.submitted-forms-report', [$submittedForm->id, 'D'])) . '"
                                            ><i class="mdi mdi-download-outline"></i>
                                        </a>
                                     </div>',
                'action' => (in_array($submittedForm->form_id,array_merge($this->minaFormsArray , $this->arafahFormsArray)) ? '
                                <a
                                class="btn btn-outline-info btn-sm m-1 on-default " target="_blank"
                                href="' . (route('admin.submitted-form.report-gov', $submittedForm->uuid ?? fakeUuid())) . '"
                                ><i class="mdi mdi-file-document-outline"></i>
                                </a>
                                ':'').
                                '<a
                                class="btn btn-outline-primary btn-sm m-1 on-default " target="_blank"
                                href="' . (route('admin.submitted-form.report', $submittedForm->uuid ?? fakeUuid())) . '"
                                ><i class="mdi mdi-file-document-outline"></i>
                                </a>
                                <a target="_blank"
                                class="btn btn-outline-success btn-sm m-1 on-default "
                                href="' . (route('admin.submitted-form.report', [$submittedForm->uuid ?? fakeUuid(), 'D'])) . '"
                                ><i class="mdi mdi-download-outline"></i>
                                </a>
                                <button class="btn btn-outline-danger btn-sm m-1 on-default m-r-5 deletesubmitted_forms" data-model-id="' . $submittedForm->id . '">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                                ',

            ];
        });

        return response()->json([
            'data' => $transformedData,
            'draw' => intval(request()->input('draw', 1)), // Required for DataTables
            'recordsTotal' => $recordsTotal, // Total records
            'recordsFiltered' => $recordsFiltered, // Filtered records (adjust if you apply filters)
        ]);
    }

    public function checkRelatives($delete_model)
    {
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }

    //??=========================================================================================================

    public function destroy(string $id)
    {
        $delete_model = $this->model::findOrFail($id);

        $submitted_sections = $delete_model->submitted_sections();
        $submitted_sections->delete();
        $delete_model->delete();

        return response(array('message' => trans("translation.Deleted successfully"), 'alert-type' => 'success'), 200);
    }
}
