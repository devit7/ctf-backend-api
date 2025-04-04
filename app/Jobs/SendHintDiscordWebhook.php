<?php

namespace App\Jobs;

use App\Models\Hints;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;


class SendHintDiscordWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $webhookUrl;
    protected $hints;

    /**
     * Create a new job instance.
     */
    public function __construct(string $webhookUrl, Hints $hints)
    {
        //
        $this->webhookUrl = $webhookUrl;
        $this->hints = $hints;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        try {
            $data = [
                'content' => "**New Hint Release!** 🎉",
                'embeds' => [
                    [
                        'description' => "==  Hint Details  ==\n" .
                            "\n**Chall Title:** `" . $this->hints->chall->title . "`" .
                            "\n**Category:** `" . $this->hints->chall->category->name . "`" .
                            "\n**Hint:** `" . $this->hints->hint . "`",
                        'color' => 0x0118D8,
                        'footer' => [
                            'text' => 'Happy solving! 🚀',
                        ],
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
            ];

            Http::post($this->webhookUrl, $data);
        } catch (\Exception $e) {
            // Handle exception if needed
        }
    }
}
