<?php

namespace App\Actions;

use App\Models\Card;
use Illuminate\Support\Facades\Crypt;

class SaveCardAction
{
    public static function execute(array $data): void
    {
        Card::create(
            [
                'user_id' => auth()->user()->id,
                'cardNumber' => Crypt::encryptString($data['cardNumber']),
                'year'=> Crypt::encryptString($data['year']),
                'month'=>  Crypt::encryptString($data['month']),
                'cvc'=> Crypt::encryptString($data['cvc']),

            ]);
    }
}
