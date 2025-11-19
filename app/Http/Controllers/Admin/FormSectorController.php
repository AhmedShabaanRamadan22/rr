<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Sector;
use App\Models\SubmittedForm;
use App\Models\User;
use App\Traits\CrudOperationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Aws\map;

class FormSectorController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model( Sector::class );
    }

    public function show(Form $form)
    {
        $columns = [
            'id' => 'id',
            'label' => 'sector-label',
            'sight' => 'sight',
            'guest_quantity' => 'guest-quantity',
            'manager_id' => 'manager_id',
            'boss_name' => 'boss',
            'supervisor_name' => 'supervisor',
            'monitors' => 'monitors',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'start_answering' => 'start-answering',
            'answered' => 'answers-completed',
        ];
        $yes_no_options = [0 => 'لا', 1=> 'نعم'];

        $filters = [
            'dates' => $this->getUniqueDates($form),
            'time_start' => null,
            'has_start' => $yes_no_options,
            'has_completed' => $yes_no_options,

        ];

        return view('admin.form_sectors.show', compact('form', 'columns', 'filters'));
    }
    public function dataTable( Request $request )
    {
        $form = Form::with( 'organization_service' )->findOrFail( \request( 'form_id' ) );

        $submitted_forms_query = SubmittedForm::with('order_sector.sector', 'form.organization_service')
            ->where('form_id', $form->id);

        if($request->has('date')){
            $submitted_forms_query->whereIn(DB::raw('DATE(created_at)'), $request->input('date'));
        }

        if($request->has('start_time')){
            if( (!isset($request->date)) || count($request->input('date')) !== 1){
                return response(['error'=>trans('translation.please-choose-only-one-date')],400);
            }else{
                $submitted_forms_query->where('created_at','>=', $request->input('date')[0] . ' ' . $request->input('start_time'));
            }
            
        }
        ($submitted_forms = $submitted_forms_query->orderByDesc('created_at')->get());

        $sector_ids_with_creates = $submitted_forms->pluck('created_at','order_sector.sector.id')->unique();
        $sector_ids_with_updates = $submitted_forms->where('is_completed',true)->pluck('updated_at','order_sector.sector.id')->unique();

        $organization_id = $form->organization_service->organization_id;

        $query = Sector::with( [ 'boss:id,name', 'supervisor:id,name', 'monitor_order_sectors.monitor.user' ] )
            ->whereHas( 'classification', function ( $query ) use ( $organization_id ) {
                $query->where( 'organization_id', $organization_id );
            } );

        if($request->has('has_start')){
            if( count($request->input('has_start')) <= 1 ){
                if($request->input('has_start')[0] == 0 ){
                    $query->whereNotIn('id',array_keys($sector_ids_with_creates->toArray()));
                }else if ($request->input('has_start')[0] == 1){
                    $query->whereIn('id',array_keys($sector_ids_with_creates->toArray()));
                }
            }
        }

        if($request->has('has_completed')){
            if( count($request->input('has_completed')) <= 1 ){
                if($request->input('has_completed')[0] == 0 ){
                    $query->whereNotIn('id',array_keys($sector_ids_with_updates->toArray()));
                }else if ($request->input('has_completed')[0] == 1){
                    $query->whereIn('id',array_keys($sector_ids_with_updates->toArray()));
                }
            }
        }

        return datatables( $query->orderByRaw( 'CAST(label AS UNSIGNED)' )->get() )
            ->editColumn( 'boss_name', function ( $row ) {
                return $row->boss->name;
            } )
            ->editColumn( 'supervisor_name', function ( $row ) {
                return $row->supervisor->name;
            } )
            ->editColumn('monitors', function ($row) {
                return $row->monitor_order_sectors->pluck('monitor.user')->unique('id')->pluck('name')->implode(', ');
            })
            ->addColumn( 'start_answering', function ( $row ) use ( $sector_ids_with_creates ) {
                //return '<i class="' . ($sector_ids->contains($row->id) ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' "></i>';
                return array_key_exists( $row->id ,$sector_ids_with_creates->toArray()) ? trans( 'translation.yes' ) : trans( 'translation.no' );
            } )
            ->addColumn( 'answered', function ( $row ) use ( $sector_ids_with_updates ) {
                //return '<i class="' . ($sector_ids->contains($row->id) ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' "></i>';
                return array_key_exists( $row->id ,$sector_ids_with_updates->toArray()) ? trans( 'translation.yes' ) : trans( 'translation.no' );
            } )
            ->editColumn('created_at', function ($row) use ( $sector_ids_with_creates ) {
                if (array_key_exists( $row->id ,$sector_ids_with_creates->toArray()) ) {
                    $created_at = $sector_ids_with_creates[$row->id];
                    return $created_at . ' (' . $created_at->diffForHumans() . ')';
                }
                return '-';
            })
            ->editColumn('updated_at', function ($row) use ( $sector_ids_with_updates ) {
                if (array_key_exists( $row->id ,$sector_ids_with_updates->toArray()) ) {
                    $updated_at = $sector_ids_with_updates[$row->id];
                    return $updated_at . ' (' . $updated_at->diffForHumans() . ')';
                }
                return '-';
            })
            ->rawColumns( [ 'color', 'answered' ] )
            ->toJson();
    }

    /**
     * Retrieve unique dates associated with a form.
     *
     * @param  \App\Models\Form  $form
     * @return \Illuminate\Support\Collection
     */
    protected function getUniqueDates(Form $form)
    {
        $submitted_forms = SubmittedForm::with('order_sector.sector', 'form.organization_service')
            ->where('form_id', $form->id)
            ->get(['created_at']);

        // Extract and format unique 'created_at' dates as yyyy-mm-dd
        $unique_dates = $submitted_forms->pluck('created_at')
            ->map(function ($date) {
                return $date->toDateString(); // Convert each Carbon instance to yyyy-mm-dd format
            })
            ->unique()
            ->sortByDesc(function ($date) {
                return Carbon::createFromFormat('Y-m-d', $date); // Sort by creating a Carbon instance for sorting
            })
            ->values();

        return $unique_dates;
    }
}