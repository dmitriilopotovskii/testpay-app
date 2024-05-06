<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class processSavedCardPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rouble' => ['required', 'numeric','min_digits:1','max_digits:6','gt:14'],
            'id' => ['required','numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
