<?php

namespace App\Services;

use App\Models\Contract;

class ContractService
{
    public function find_contract($contractable_id, $contract_template_type)
    {
        $contract = Contract::with('contract_template')->where(['contractable_id' => $contractable_id])
            ->whereHas('contract_template', function ($q) use ($contract_template_type) {
                $q->where('type', $contract_template_type);
            })->first();
        return $contract;
    }
}