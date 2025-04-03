<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chall extends Model
{
    //
    protected $table = 'chall';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hints()
    {
        return $this->hasMany(Hints::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submisions::class);
    }

    public function correctSubmissions()
    {
        return $this->hasMany(Submisions::class)->where('status', 'correct');
    }
}
