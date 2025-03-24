<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hints extends Model
{
    //
    protected $table = 'hints';
    protected $guarded = ['id'];

    public function chall()
    {
        return $this->belongsTo(Chall::class);
    }
}
