<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class PaymentController extends Controller
{
    public function createSession()
    {
        Stripe::setApiKey('sk_test_51Pqki0EZ47S62tpg1flfMt0Klt0viokE6rcjDwRTYM26xAObMzOnvGAsV6Ibu5algw6DnjwVYjECFtWlMAZvOFod0074vvPj6F');

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
                    'unit_amount' => 50000, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session'
            ],
            'locale' => 'auto',
            'submit_type' => 'pay',
        ]);

        return redirect($session->url, 303);
    }

    public function success(Request $request)
    {

        $user = User::find(Auth::id());
        $user->cotisation = true;
        $user->save();

        return redirect()->route('services')->with('success', 'Votre paiement a été effectué avec succès. Merci de votre adhésion.');
    }


    public function cancel(Request $request)
    {
        return redirect()->route('services')->with('error', 'Paiement annulé. Votre adhésion n\'a pas été renouvelée.');
    }

}
