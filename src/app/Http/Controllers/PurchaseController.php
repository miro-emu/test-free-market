<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class PurchaseController extends Controller
{
    // 購入画面
    public function confirm($item_id)
    {
        if (!Auth::check()) {
                return redirect('/login');
            }
            
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        
        if (session()->has('shipping_address_id')) {
            $address = $user->addresses()
                ->where('id', session('shipping_address_id'))
                ->first();
        } else {
            $address = $user->addresses()
                ->where('type', Address::TYPE_PROFILE)
                ->first();
        }

        return view('purchase',compact('item','user','address'));
    }

    // 住所変更
    public function address($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('address',compact('item'));
    }

    public function createAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $address = $user->addresses()->updateOrCreate(
            ['type' => Address::TYPE_SHIPPING],
            [
                'postal_code' => $request->postal_code,
                'address_line' => $request->address_line,
                'building' => $request->building
            ]
        );

        session(['shipping_address_id' => $address->id]);

        return redirect('/purchase/'.$item_id);
    }

    // 購入
    public function purchase(PurchaseRequest $request, $item_id)
    {   
        $user = Auth::user();

        $item = Item::findOrFail($item_id);

        if ($item->order) {
            return redirect('/')->with('error', '売り切れです');
        }

        if (session()->has('shipping_address_id')) {
            $address = $user->addresses()
                ->where('id', session('shipping_address_id'))
                ->first();
        } else {
            $address = $user->addresses()
                ->where('type', Address::TYPE_PROFILE)
                ->firstOrFail();
        }

        if (!$address) {
            return back()->withErrors([
                'address' => '配送先を設定してください'
            ]);
        }

        // コンビニ払い
        if ($request->payment_method == 1) {

            Order::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => 1,
                'payment_status' => 'pending',
                'address_id' => $address->id
            ]);
            session()->forget('shipping_address_id');

            return redirect('/');
        }

        // カード決済
        if ($request->payment_method == 2) {

            Stripe::setApiKey(config('services.stripe.secret'));

            $order = Order::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => 2,
                'payment_status' => 'pending',
                'address_id' => $address->id
            ]);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',

                'metadata' => [
                    'order_id' => $order->id,
                ],

                'success_url' => url('/'),
                'cancel_url' => url('/purchase/'.$item->id),
            ]);

            $order->update([
                'payment_ref_id' => $session->id
            ]);

            session()->forget('shipping_address_id');

            return redirect($session->url);
        }
    
    }

    public function success($item_id)
    {
        $order = Order::where('item_id', $item_id)
            ->where('payment_method', 2)
            ->latest()
            ->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now()
        ]);

        return redirect('/')->with('success','購入が完了しました');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $secret
            );
        } catch (\Exception $e) {
            return response('Invalid', 400);
        }

        // 決済成功イベント
        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $orderId = $session->metadata->order_id;

            $order = Order::find($orderId);

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);
            }
        }

        return response('OK', 200);
    }

}
