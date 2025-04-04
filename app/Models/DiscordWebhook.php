<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordWebhook extends Model
{
    use HasFactory;

    protected $table = 'discord_webhook';

    protected $guarded = ['id'];
}
