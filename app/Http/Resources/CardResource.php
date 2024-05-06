<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class CardResource extends JsonResource
{
    public static $wrap = null;
    /** @mixin \App\Models\Card */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cardNumber' => '****'.substr(Crypt::decryptString($this->cardNumber), -4),
            'month' => Crypt::decryptString($this->month),
            'year' => Crypt::decryptString($this->year),
        ];
    }
}
