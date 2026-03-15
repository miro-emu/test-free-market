<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 購入画面
    public function confirm($item_id)
    {
        $user = Auth::user();

        $item = Item::findOrFail($item_id);
        
        $address = $user->addresses()
            ->where('type', Address::TYPE_SHIPPING)
            ->first();
        
        if (!$address) {
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

        return redirect('/purchase/'.$item_id);
    }

    // 購入
    public function purchase(Request $request, $item_id)
    {   
        $user = Auth::user();

        $item = Item::findOrFail($item_id);

        if ($item->order) {
            return redirect('/');
        }

        $address = $user->addresses()
            ->where('type', Address::TYPE_SHIPPING)
            ->first();
        if (!$address) {
            $address = $user->addresses()
                ->where('type', Address::TYPE_PROFILE)
                ->firstOrFail();
        }

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'address_id' => $address->id
        ]);

        return redirect('/');
    }

}
