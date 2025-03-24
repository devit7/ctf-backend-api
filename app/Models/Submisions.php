<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submisions extends Model
{
    //

    protected $table = 'submisions';
    protected $guarded = ['id'];

    public function chall()
    {
        return $this->belongsTo(Chall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
