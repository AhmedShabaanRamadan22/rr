<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttachmentLabel;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\Facility;
use App\Models\Order;
use App\Models\OrderSector;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\User;
use App\Traits\AttachmentTrait;
use App\Traits\PdfTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\ContractService;

use function PHPUnit\Framework\isNull;
use function PHPUnit\Framework\matches;

class ContractController extends Controller
{
    use AttachmentTrait;
    use PdfTrait;

    protected $errorKeyFlag = false;

    private $contractService;


    public function blade()
    {
        $contract = 'test';
        return $this->getBladeTemplate('pdf', $contract);
    }

    //?? ===========================================================================================

    public function preview(Request $request)
    {
        return $this->generate_contract($request->contractable_id, $request->contractable_type, $request->contract_template);
    }

    //?? ===========================================================================================
    public function download(Request $request)
    {
        return $this->generate_contract($request->contractable_id, $request->contractable_type, $request->contract_template, null, 'D');
    }

    //?? ===========================================================================================

    public function generate_contract($contractable_id, $contractable_type, $contract_template, $filename = "contract.pdf", $output = "I")
    {
        // $this->generate_contract($contract->contractable_id, $contract->contract_template->type,$filepath, "F");
        // dd($contract_template);
        $contract_template = $this->getContract($contract_template);
        if ($contractable_type == 'App\Models\OrderSector') {
            $dictionary = $this->getOrderSectorDictionary($contractable_id);
        } else {
            $dictionary = $this->getEmployeeDictionary($contractable_id, $contract_template->organization_id);
        }
        $contract = $this->getContractContent($contract_template->type);
        if (!$contract) {
            return back()->with(['message' => trans('translation.no-contract-template-found'), 'alert-type' => 'error']);
        }

        // dd($dictionary,$this->getMatches($contract));

        // Extracted text will be in $matches[1]
        foreach ($this->getMatches($contract) as $item) {
            $replaced = $this->getValueFromArray($dictionary, trim($item));
            $contract = str_replace("{{" . $item . "}}", $replaced, $contract);
        }

        // return if create new contract and have keys errors
        if ($this->errorKeyFlag && $output == 'F') {
            return false;
        }
        $this->setPdfData([
            "body_content" => $contract,
        ]);
        $mpdf = $this->mPdfInit("contract");
        // dd($filename,$output);
        return $mpdf->Output($filename, $output);
        /**
         * For the output function second parameter
         * D = force to download
         * I = view online through the browser
         * F = save to local file
         * S = return the document as string
         */
    }

    //?? ===========================================================================================

    function getContractContent($contract_template_type)
    {
        $contract_template = $this->getContract($contract_template_type);
        if ($contract_template) {
            return $contract_template->content;
        }
        return false;
    }

    //?? ===========================================================================================

    function getContract($contract_template_type)
    {
        $contract_template = ContractTemplate::where('type', $contract_template_type)->first();
        return $contract_template;
    }

    //?? ===========================================================================================

    function getOrderSectorDictionary($order_sector_id)
    {
        $order_sector = OrderSector::find($order_sector_id);
        $organization = Organization::with('chairman')->find($order_sector->sector->classification->organization_id)->toArray();
        $sector = Sector::with('classification.organization')->find($order_sector->sector_id)->toArray();
        $order = Order::with('facility', 'user')->find($order_sector->order_id)->toArray();
        //dd($order);
        return $dictionary = compact('organization', 'sector', 'order');
    }

    //?? ===========================================================================================

    function getEmployeeDictionary($user_id, $organization_id)
    {
        $user = User::with('iban')->find($user_id);
        $organization = Organization::with('chairman')->find($organization_id)->toArray();
        $user = $user->toArray();
        // $sector = Sector::with('classification.organization')->find($order_sector->sector_id)->toArray();
        // $order = Order::with('facility','user')->find($order_sector->order_id)->toArray();
        return $dictionary = compact('organization', 'user');
    }

    //?? ===========================================================================================

    function getMatches($contract)
    {

        // Define the characters to search for (e.g., square brackets)
        $startChar = '{{';
        $endChar = '}}';

        // Construct the regular expression pattern
        $pattern = "/$startChar(.*?)$endChar/";

        // Perform the regular expression match
        preg_match_all($pattern, $contract, $matches);

        return $matches[1];
    }

    //?? ===========================================================================================

    function getValueFromArray($array, $path)
    {
        // if (!is_array($array)) {
        //     return "{Key not fou}";
        // }

        $keys = explode('##', $path);
        foreach ($keys as $key) {
            // Check if the key exists in the current array
            if (!is_array($array)) {
                $this->errorKeyFlag = true;
                // dd($keys);
                return "{Not  array: $key }";
            }

            if (array_key_exists($key, $array)) {
                $array = $array[$key];
                // dd(($array));
            } else {
                // Key not found, return null or handle accordingly
                $this->errorKeyFlag = true;
                return "{ Not found key: $key }";
            }
        }

        return $array;
    }

    //?? ===========================================================================================

    public function store(Request $request)
    {
        $contract_template = ContractTemplate::orderByDesc('created_at')->where('type', $request->contract_template)->get()->first();

        $contractable_type = $request->contractable_type;
        $cont = $contractable_type::find($request->contractable_id);

        $contract = $cont->contracts()->create([
            'user_id' => auth()->user()->id,
            'is_approved_' => 0,
            'started_at' => Carbon::now(),
            'contract_template_id' => $contract_template->id,
            'sign_date' => Carbon::now()
        ]);

        if (isset($request->salary)) {
            $cont->update(['salary' => $request->salary]);
        }

        // $new_contract = Contract::create([
        //     'user_id' => auth()->user()->id,
        //     'is_approved_' => 1,
        //     'started_at' => Carbon::now(),
        //     'order_sector_id' => $order_sector_id,
        //     'contract_template_id' => $contract_template_id,
        //     'sign_date' => Carbon::now()
        // ]);

        $this->storeContractAttachment($contract);
        if ($this->errorKeyFlag) {
            $contract->delete();
            return request()->is('admin/api/*')
              ? back()->with(['message' => trans('translation.some-error-in-keys'), 'alert-type' => 'error'], 400)
              : response(['message' => trans('translation.some-error-in-keys'), 'alert-type' => 'error'], 400);
        }
        return request()->is('admin/api/*')
            ? back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success'], 200)
            : response(['message' => trans('translation.Added successfully'), 'alert-type' => 'success'], 200);
    }

    //?? ===========================================================================================

    public function destroy(Contract $contract)
    {
        // dd($contract);
        $contract->delete();
        return response(['message' => trans('translation.Added successfully')], 200);
    }

    //?? ===========================================================================================

    public function regenerate_contract(Contract $contract)
    {
        request()->merge(['contractable_id' => $contract->contractable_id, 'contractable_type' => $contract->contractable_type, 'contract_template' => $contract->contract_template->type]);
        // dd(request()->all());
        $contract->delete();
        // dd($contract->order_sector, $contract->contract_template->type);
        
        $response = $this->store(request());
        if($response->status() == 200){
            return response(['message' => trans('translation.regenerated successfully')], 200);
        }
        Contract::withTrashed()->find($contract->id)->restore();
        return response(['message' => trans('translation.something went wrong')], 400);
    }


    //?? ===========================================================================================

    public function storeContractAttachment($contract)
    {
        $filename = time() . '_contract_id' . $contract->id . '.pdf';
        $subPath = 'public/contracts/' . $contract->contract_template_id . '/' . $contract->contractable_id;
        $fullPath = $this->getOrCreateDirectory($subPath);

        // Create the dynamic path
        $filepath = $fullPath . $filename;

        // dd($filename,$path,$filepath);
        // $this->generate_contract($contract->contractable_id, $contract->contract_template->type,$filepath, "F");
        $this->generate_contract($contract->contractable_id, $contract->contractable_type, $contract->contract_template->type, $filepath, "F");

        if (!$this->errorKeyFlag) {
            $this->insert_attachment($filename, $subPath, $contract, auth()->user()->id ?? 0, AttachmentLabel::CONTRACT_LABEL);
        }
    }

    public function getOrCreateDirectory($subPath)
    {
        $basePath = storage_path('app/');
        $fullPath = $basePath . '/' . $subPath . '/';
        if (!Storage::exists($subPath)) {
            Storage::makeDirectory($subPath);
        }
        return $fullPath;
    }

    public function storeSignedContract(Request $request){
        $contract = Contract::findOrFail($request->contract_id);

        $this->store_attachment($request->signed_contract, $contract, AttachmentLabel::SIGNED_CONTRACT_LABEL, null, auth()->user()->id ?? 0);
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success'], 200);
    }

    public function destroySignedContract($contract_id){
        $contract = Contract::findOrFail($contract_id);
        if(!$contract->has_signed_contract){
            return response(['message' => trans('translation.no-signed-contract')], 400);
        }
        $contract->signedContract()->delete();
        return response(['message' => trans('translation.delete-successfully')], 200);
    }
}