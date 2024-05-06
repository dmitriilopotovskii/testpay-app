<?php

namespace App\Actions;

use App\Models\Card;

class SaveCardAction
{
    public static function execute(array $data): void
    {
        Card::create(
            [
                'user_id' => auth()->user()->id,
                ...$data,
            ]);
    }
}
