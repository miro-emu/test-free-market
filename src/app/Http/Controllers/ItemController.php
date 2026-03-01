<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    // 商品一覧
    public function items()
    {
        $items = Item::all();

        return view('items',compact('items'));
    }

    // 商品詳細
    public function getDetail($item_id)
    {
        $item=Item::with('categories')->findOrFail($item_id);
        $categories = Category::all();
        $conditions = Condition::all();

        return view('detail', compact('item','categories','conditions'));
    }
}
