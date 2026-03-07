<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    public function likes()
    {
        return $this->hasMany(Like::class, 'item_id');
    }

    public function is_liked_by_auth_user()
    {
        $id = Auth::id();

        $likers = array();
        foreach($this->likes as $like) {
        array_push($likers, $like->user_id);
        }

        if (in_array($id, $likers)) {
        return true;
        } else {
        return false;
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
}
