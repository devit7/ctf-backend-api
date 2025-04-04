<?php

namespace App\Observers;

use App\Jobs\SendHintDiscordWebhook;
use App\Models\DiscordWebhook;
use App\Models\Hints;

class HintObserver
{
    /**
     * Handle the Hints "created" event.
     */
    public function created(Hints $hints): void
    {
        //
        // You can add logic here if needed when a hint is created
        // For example, you might want to log the creation or send a notification
    
        $webhook = DiscordWebhook::where('type', 'hint')
            ->where('status', 'active')
            ->first();

        if ($webhook) {
            // Dispatch job to queue instead of sending directly
            SendHintDiscordWebhook::dispatch($webhook->url, $hints);
        }
    }

    /**
     * Handle the Hints "updated" event.
     */
    public function updated(Hints $hints): void
    {
        //
    }

    /**
     * Handle the Hints "deleted" event.
     */
    public function deleted(Hints $hints): void
    {
        //
    }

    /**
     * Handle the Hints "restored" event.
     */
    public function restored(Hints $hints): void
    {
        //
    }

    /**
     * Handle the Hints "force deleted" event.
     */
    public function forceDeleted(Hints $hints): void
    {
        //
    }
}
