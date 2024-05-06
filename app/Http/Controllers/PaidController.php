<?php

namespace App\Http\Controllers;

use App\Actions\SaveCardAction;
use App\Http\Requests\CardPayRequest;
use App\Http\Resources\CardResource;
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


}