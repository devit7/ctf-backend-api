<?php

namespace App\Jobs;

use App\Models\Chall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendChallDiscordWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $webhookUrl;
    protected $chall;

    /**
     * Create a new job instance.
     *
     * @param string $webhookUrl
     * @param Chall $chall
     * @return void
     */
    public function __construct(string $webhookUrl, Chall $chall)
    {
        $this->webhookUrl = $webhookUrl;
        $this->chall = $chall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $data = [
                'content' => "**New Challenge Created!** 🎉",
                'embeds' => [
                    [
                        'title' => "|-- " . strtoupper($this->chall->title) . " --|",
                        'description' => "==  Challenge Details  ==\n" .
                            "\n**Points:** `" . $this->chall->point . "` Pts" .
                            "\n**Category:** `" . $this->chall->category->name .
                            "`\n**Author:** `" . $this->chall->author . "`",
                        'color' => 0x00FF00,
                        'footer' => [
                            'text' => 'Happy solving! 🚀',
                        ],
                        'timestamp' => now()->toIso8601String(),
                    ],
                ],
            ];

            Http::post($this->webhookUrl, $data);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
