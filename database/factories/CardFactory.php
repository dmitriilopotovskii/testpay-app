<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        return [
            'cardNumber' => Crypt::encryptString($this->faker->creditCardNumber()),
            'cvc' => Crypt::encryptString($this->faker->randomNumber('3')),
            'month' => Crypt::encryptString(12),
            'year' => Crypt::encryptString(25),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => 1,
        ];
    }
}
