<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    const TYPE_PROFILE = 1;
    const TYPE_SHIPPING = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
