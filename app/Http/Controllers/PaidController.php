<?php

namespace App\Http\Controllers;

use App\Actions\SaveCardAction;
use App\Http\Requests\CardPayRequest;
use App\Http\Requests\processSavedCardPaymentRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PaidController extends Controller
{
    public function index()
    {
        $cards = auth()->user()->cards()->get();
        $cardResource = CardResource::collection($cards)->resolve();
        return Inertia::render('Paid', [
            'success' => session('success'),
            'cards' => $cardResource
        ]);
    }

    public function pay(CardPayRequest $request)
    {
        $request->validated();
        if ($request->has('remember')) {
            SaveCardAction::execute($request->all());
        }
        // Simulate payment processing

        return to_route('paid.index')
            ->with('success', 'Payment was successful');
    }

    public function processSavedCardPayment(processSavedCardPaymentRequest $request)
    {
        $request->validated();

        $card = Card::findOrFail($request->id);
        $card->cardNumber = Crypt::decryptString($card->cardNumber);
        $card->year = Crypt::decryptString($card->year);
        $card->month = Crypt::decryptString($card->month);
        $card->cvc = Crypt::decryptString($card->cvc);
        $ammount = $request->rouble;
        if (Gate::allows('paid-card', $card)) {
            // Simulate payment processing


            return to_route('paid.index')
                ->with('success', 'Payment was successful');
        }

        abort(403);
    }


}
