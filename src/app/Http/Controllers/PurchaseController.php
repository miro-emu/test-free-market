<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Address;

class PurchaseController extends Controller
{
    // 購入画面
    public function confirm($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase',compact('item'));
    }

    // 住所変更
    public function address()
    {
        return view('address');
    }

    public function createAddress(Request $request)
    {
        // Address::create([
        // 'user_id' => Auth::id(),
        // 'postal_code' => $request->postal_code,
        // 'address_line' => $request->address_line,
        // 'building' => $request->building
        // ]);

        $address->create(
            ['type' => Address::TYPE_SHIPPING],
            [
                'postal_code' => $request->postal_code,
                'address_line' => $request->address_line,
                'building' => $request->building
            ]
        );

        return redirect('/item/'.$item_id);
    }

}
