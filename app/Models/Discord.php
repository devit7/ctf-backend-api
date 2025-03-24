<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discord extends Model
{
    //
    protected $table = 'discord_webhook';
    protected $guarded = ['id'];
}
