<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧
    public function index(Request $request)
    {   
         $keyword = $request->input('keyword');
        $tab = $request->input('tab');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $query = Item::query()->whereRaw('0 = 1'); 
            } else {
                $query = Item::whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            }
        } else {
            if (Auth::check()) {
                $query = Item::where('user_id', '!=', Auth::id());
            } else {
                $query = Item::query();
            }
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        $items = $query->with('order')->get();

        return view('items', compact('items', 'keyword', 'tab'));
    }
    
    // 商品詳細
    public function getDetail($item_id)
    {
        $item=Item::with(['categories','condition','comments.user'])->findOrFail($item_id);

        return view('detail', compact('item'));
    }

    // コメント
    public function createComment(CommentRequest $request,$item_id)
    {
        if (!Auth::check()) {
                return redirect('/login');
            }

        $item = Item::with('comments.user')->findOrFail($item_id);
        Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item_id,
        'content' => $request->content
        ]);
        return redirect('/item/'.$item_id);
    }


    // いいね機能
    public function like($id)
    {
        if (!Auth::check()) {
                return redirect('/login');
            }

        Like::create([
        'item_id' => $id,
        'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'You Liked the Item.');

        return redirect()->back();
    }

    public function unlike($id)
    {
        if (!Auth::check()) {
                return redirect('/login');
            }

        $like = Like::where('item_id', $id)->where('user_id', Auth::id())->first();
        $like->delete();

        session()->flash('success', 'You Unliked the Item.');

        return redirect()->back();
    }

    // 出品
    public function sell()
    {
        if (!Auth::check()) {
                return redirect('/login');
            }

        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories','conditions'));
    }

    public function createSell(ExhibitionRequest $request)
    {
        $form = $request->all();
        $image = $request->file('image');
        $path = isset($image) ? $image->store('items', 'public') : '';
        
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'image' => $path,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'condition_id' => $request->condition_id,
        ]);
        $item->categories()->sync($request->category_ids ?? []);

        return redirect('/');
    }
}
