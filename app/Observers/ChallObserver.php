<?php

namespace App\Observers;

use App\Models\Chall;
use App\Models\DiscordWebhook;
use App\Jobs\SendChallDiscordWebhook;

class ChallObserver
{
    /**
     * Handle the Chall "created" event.
     */
    public function created(Chall $chall): void
    {
        try {
            $webhook = DiscordWebhook::where('type', 'chall')
                ->where('status', 'active')
                ->first();

            if ($webhook) {
                // Dispatch job to queue instead of sending directly
                SendChallDiscordWebhook::dispatch($webhook->url, $chall);
            }
        } catch (\Exception $e) {
            // Silently handle the error
        }
    }

    /**
     * Handle the Chall "updated" event.
     */
    public function updated(Chall $chall): void
    {
        //
    }

    /**
     * Handle the Chall "deleted" event.
     */
    public function deleted(Chall $chall): void
    {
        //
    }

    /**
     * Handle the Chall "restored" event.
     */
    public function restored(Chall $chall): void
    {
        //
    }

    /**
     * Handle the Chall "force deleted" event.
     */
    public function forceDeleted(Chall $chall): void
    {
        //
    }
}
