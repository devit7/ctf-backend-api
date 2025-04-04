<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendCorrectSubmissionDiscordWebhook;
use App\Models\Category;
use App\Models\Chall;
use App\Models\DiscordWebhook;
use App\Models\Submisions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallController extends Controller
{
    public function submitFlag(Request $request)
    {
        $request->validate([
            'chall_id' => 'required|exists:chall,id',
            'flag' => 'required|string'
        ]);

        $chall = Chall::find($request->chall_id);
        $user = Auth::user();

        // Check if already solved
        $existingSubmission = Submisions::where('user_id', $user->id)
            ->where('chall_id', $chall->id)
            ->where('status', 'correct')
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'message' => 'You have already solved this challenge'
            ], 400);
        }

        $status = $chall->flag === $request->flag ? 'correct' : 'incorrect';

        if ($status === 'correct') {
            // Create the submission first
            $submission = Submisions::create([
                'user_id' => $user->id,
                'chall_id' => $chall->id,
                'flag_submited' => $request->flag,
                'status' => $status
            ]);

            // Get all correct submissions for this challenge sorted by creation time
            $correctSubmissions = Submisions::where('chall_id', $chall->id)
                ->where('status', 'correct')
                ->orderBy('created_at', 'asc')
                ->get();

            // Find the position of the current submission
            $position = $correctSubmissions->search(function ($item) use ($submission) {
                return $item->id === $submission->id;
            }) + 1; // +1 because array indices start at 0

            // Send to Discord
            $webhook = DiscordWebhook::where('type', 'submision')
                ->where('status', 'active')
                ->first();

            if ($webhook) {
                // Dispatch job to queue instead of sending directly
                SendCorrectSubmissionDiscordWebhook::dispatch($webhook->url, $position, $chall->title, $user->username);
            }
            // Send the message to Discord

        } else {
            // Only create submission if incorrect (since we already created it above for correct submissions)
            Submisions::create([
                'user_id' => $user->id,
                'chall_id' => $chall->id,
                'flag_submited' => $request->flag,
                'status' => $status
            ]);
        }

        return response()->json([
            'message' => $status === 'correct' ? 'Correct flag!' : 'Wrong flag!',
            'status' => $status
        ]);
    }

    public function listChallsByUser()
    {
        $user = Auth::user();
        $challs = Chall::with(['hints', 'category'])
            ->where('status', 'open')
            ->withCount(['submissions as solved' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', 'correct');
            }])
            //get total solved
            ->withCount(['submissions as total_solved' => function ($query) {
                $query->where('status', 'correct');
            }])
            ->get()
            ->map(function ($chall) {
                $chall->is_solved = $chall->solved > 0;
                unset($chall->solved);
                unset($chall->flag);
                return $chall;
            });

        return response()->json($challs);
    }

    public function leaderboard()
    {
        $users = User::select(['id', 'username', 'email'])
            ->withCount(['submissions as solved_count' => function ($query) {
                $query->where('submisions.status', 'correct');
            }])
            ->withSum(['submissions as total_points' => function ($query) {
                $query->where('submisions.status', 'correct')
                    ->join('chall', 'submisions.chall_id', '=', 'chall.id');
            }], 'chall.point') // Note: we're directly summing chall.point
            ->where("users.role", "user")
            ->orderBy('solved_count', 'desc')
            ->orderBy('total_points', 'desc')
            ->take(100)
            ->get();

        return response()->json($users);
    }

    public function getSubmissionsByChall($chall_id)
    {
        $submissions = Submisions::where('chall_id', $chall_id)
            ->with(['user:id,username,email'])
            ->where('status', 'correct')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($submissions);
    }

    public function getCategorys()
    {
        $categorys = Category::select(['id', 'name'])
            ->get();

        return response()->json($categorys);
    }
}
