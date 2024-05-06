<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    public static $wrap = null;
    /** @mixin \App\Models\Card */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cardNumber' => '****'.substr($this->cardNumber, -4),
            'month' => $this->month,
            'year' => $this->year,
        ];
    }
}
