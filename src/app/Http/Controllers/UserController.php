<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // マイページ
    public function mypage(Request $request)
    {
        $user = Auth::user();

        if ($request->page === 'buy'){
            $items = Item::whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } else {
                $items = Item::where('user_id' , $user->id)->get();
            }

        return view('profile', compact('user','items'));
    }

    // プロフィール編集画面
    public function edit()
    {
        $user = Auth::user();
        $address = $user->addresses()
        ->where('type', Address::TYPE_PROFILE)
        ->firstOrNew();

        return view('edit', compact('user','address'));
    }

    public function updateProfile(AddressRequest $request)
    {
        $user = Auth::user();
        $path = $user->image;

        if ($request->hasFile('image')) {
            if ($user->image) {
            Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('profile', 'public');
        }
        $user->update([
            'name' => $request->name,
            'image' => $path
        ]);

        $user->addresses()->updateOrCreate(
            ['type' => Address::TYPE_PROFILE],
            [
                'postal_code' => $request->postal_code,
                'address_line' => $request->address_line,
                'building' => $request->building
            ]
        );

        return redirect('/');
    }
}
