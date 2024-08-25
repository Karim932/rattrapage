<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createSession()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Adhésion annuelle',
                        'description' => 'Adhésion pour un an à nos services exclusifs',
                        'images' => ['http://51.210.101.132/picture/payment.webp'],
                    ],
                    'unit_amount' => 50000, // Prix en cents, exemple ici 50 USD
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success'),
            'cancel_url' => route('cancel'),
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session'
            ],
            'locale' => 'auto',
            'submit_type' => 'pay',
        ]);

        return redirect($session->url, 303);
    }

}
