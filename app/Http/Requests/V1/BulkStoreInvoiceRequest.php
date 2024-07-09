<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;

        $user = $this->user();
        return $user != null && $user->tokenCan('create');
        // return $user != null && $user->tokenCan('invoice:create'); // you can limit certain resources as well - deeper level
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // data: [
        //     {},{}
        // ]
        // if we passed data this way then need to be data.*.customerId
        // but we pass data this way. [{customerId:1, status:'B' }, {customerId:2, status:'P' }]
        return [
            '*.customerId' => ['required', 'integer'], // when checking validation of array of multiple objects do this way to validate
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billedDate' => ['required', 'date-format:Y-m-d H:i:s'],
            '*.paidDate' => ['date-format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    // transform camelcase properies into snake case
    protected function prepareForValidation() {
        $data = [];

        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            $data[] = $obj;
        }

        $this->merge($data);

        
    }

}
