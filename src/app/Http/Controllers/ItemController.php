<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
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
        $items = Item::with('order')
            ->where('user_id','!=',Auth::id())
            ->get();

        if ($request->tab === 'mylist'){
            if (!Auth::check()) {
                $items = collect();
            }
            $items = Item::whereHas('likes', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();
        }
         else {
            if (Auth::check()) {
                $items = Item::where('user_id', '!=', Auth::id())->get();
            } else {
                $items = Item::all();
            }
        }

        return view('items',compact('items'));
    }

    // 検索機能
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = Item::query();

        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $items = $query->get();
        return view('items',compact('items'));
    }

    // 商品詳細
    public function getDetail($item_id)
    {
        $item=Item::with(['categories','condition','comments.user'])->findOrFail($item_id);
        // $comments = Comment::all();

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

    public function createSell(Request $request)
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
        $item->categories()->sync($request->categories ?? []);

        return redirect('/');
    }
}
