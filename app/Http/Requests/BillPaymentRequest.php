<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'bill_instance_id' => ['required', 'exists:bill_instances,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'paid_date' => ['required', 'date'],
            'notes' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
