<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chall;
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

        $status = $chall->flag === $request->flag ? 'correct' : 'wrong';
        
        Submisions::create([
            'user_id' => $user->id,
            'chall_id' => $chall->id,
            'flag' => $request->flag,
            'status' => $status
        ]);

        return response()->json([
            'message' => $status === 'correct' ? 'Correct flag!' : 'Wrong flag!',
            'status' => $status
        ]);
    }

    public function listChallsByUser()
    {
        $user = Auth::user();
        $challs = Chall::with(['category'])
            ->withCount(['submissions as solved' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', 'correct');
            }])
            ->get()
            ->map(function($chall) {
                $chall->is_solved = $chall->solved > 0;
                unset($chall->solved);
                unset($chall->flag);
                return $chall;
            });

        return response()->json($challs);
    }

    public function leaderboard()
    {
        $users = User::select(['id', 'name', 'email'])
            ->withCount(['submissions as solved_count' => function($query) {
                $query->where('submisions.status', 'correct');
            }])
            ->withSum(['submissions as total_points' => function($query) {
                $query->where('submisions.status', 'correct')
                    ->join('chall', 'submisions.chall_id', '=', 'chall.id')
                    ->select('chall.point');
            }], 'point')
            ->orderBy('solved_count', 'desc')
            ->orderBy('total_points', 'desc')
            ->take(100)
            ->get();

        return response()->json($users);
    }
}
