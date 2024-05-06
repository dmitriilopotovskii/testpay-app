<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationYear;

class CardPayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rouble' => ['required', 'numeric','min_digits:1','max_digits:6','gt:14'],
            'cardNumber' => ['required','numeric','max_digits:16','min_digits:16'],
            'cvc' => ['required', 'numeric', 'max_digits:3'],
            'month' => ['required', 'numeric', 'max_digits:2', $this->get('year')? new CardExpirationMonth($this->get('year')): new CardExpirationMonth('00')],
            'year' => ['required', 'numeric', 'max_digits:2',$this->get('month')? new CardExpirationYear($this->get('month')): new CardExpirationYear('00')],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
