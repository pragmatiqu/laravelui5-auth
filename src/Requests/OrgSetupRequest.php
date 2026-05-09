<?php

namespace LaravelUi5\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrgSetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'legal_form'        => ['required', 'integer', 'in:1,2'],
            'org_name'          => ['required', 'string', 'max:255'],
            'company_size'      => ['required', 'string', 'in:solo,small,growing,mid_market,enterprise'],
            'function'          => ['required', 'string', 'in:developer,tech_lead,architect,executive,other'],
            'marketing_consent' => ['boolean'],
        ];
    }
}
