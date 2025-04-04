<?php

namespace App\Jobs;

use App\Models\Submisions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendCorrectSubmissionDiscordWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $webhookUrl;
    protected $position;
    protected $chall_name;
    protected $username;

    /**
     * Create a new job instance.
     */
    public function __construct(string $webhookUrl, $position, $chall_name, $username)
    {
        $this->webhookUrl = $webhookUrl;
        $this->position = $position;
        $this->chall_name = $chall_name;
        $this->username = $username;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //dd($this->position, $this->chall_name);
        // jika posisi 1 maka First Blood, jika posisi 2 maka Second Blood, dst. jika lebih dari 3 maka biasaj saja

        $blood = '';
        if ($this->position == 1) {
            $blood = "🩸 **First Blood** 🏆";
        } elseif ($this->position == 2) {
            $blood = '🥈 **Second Blood** 🔥';
        } elseif ($this->position == 3) {
            $blood = '🥉 **Third Blood** ✨';
        } else {
            $blood = '🎯 **Solved!** ⚡';
        }

        try {
            $data = [
                'content' => '',
                'embeds' => [
                    [
                        'title' => $blood,
                        'description' => "A challenger has successfully solved the challenge **{$this->chall_name}**! Congratulations! 🎉",
                        'color' => 0xFF2DF1,
                        'fields' => [
                            [
                                'name' => 'User',
                                'value' => "**{$this->username}**",
                                'inline' => true
                            ],
                            [
                                'name' => 'Challenge',
                                'value' => "**{$this->chall_name}**",
                                'inline' => true
                            ],
                            [
                                'name' => 'Position',
                                'value' => "#$this->position",
                                'inline' => true
                            ],
                        ],
                        'footer' => [
                            'text' => 'CoderCTF - Keep hacking and learning!'
                        ],
                        'timestamp' => date('c')
                    ]
                ]
            ];

            Http::post($this->webhookUrl, $data);
        } catch (\Exception $e) {
            // Handle exception if needed
            //dd($e->getMessage());
        }
    }
}
