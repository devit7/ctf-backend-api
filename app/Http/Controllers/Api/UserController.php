<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chall;
use App\Models\Submisions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function list()
    {
        $users = User::select(['username', 'email',  'institution', 'created_at'])
            ->where('role', '!=', 'superadmin')
            ->where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate(
            [
                'username' => 'required|string|max:255',
                'institution' => 'required|string|max:50',
                'bio' => 'nullable|string|max:100',
            ],
            [
                'username.required' => 'Username is required',
                'institution.required' => 'Institution is required',
                'bio.max' => 'Bio cannot exceed 255 characters',
            ]
        );

        // Cek apakah username berubah dan sudah digunakan oleh user lain
        if ($request->username !== $user->username) {
            $existingUser = User::where('username', $request->username)
                ->where('id', '!=', $user->id)
                ->first();
            if ($existingUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Username already exists'
                ], 422);
            }
            $user->username = $request->username;
        }

        // Update hanya field yang berubah
        if ($request->institution !== $user->institution) {
            $user->institution = $request->institution;
        }

        if ($request->bio !== $user->bio) {
            $user->bio = $request->bio;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user->only(['username', 'email', 'institution', 'bio'])
        ]);
    }

    public function getAllCountCategorySolvedByUser()
    {
        $user = Auth::user();
        $challs = Submisions::where('user_id', $user->id)
            ->where('status', 'correct')
            ->with('chall.category')
            ->get();
        $categoryCount = [];
        foreach ($challs as $chall) {
            $categoryName = $chall->chall->category->name;
            if (!isset($categoryCount[$categoryName])) {
                $categoryCount[$categoryName] = 0;
            }
            $categoryCount[$categoryName]++;
        }
        $totalSolved = $challs->count();
        return response()->json([
            'total_solved' => $totalSolved,
            'category_count' => $categoryCount
        ]);
    }

    public function getUserWithRankAndScore()
    {
        $user = Auth::user();
        $data = User::withSum(['submissions as total_points' => function ($query) {
            $query->where('submisions.status', 'correct')
                ->join('chall', 'submisions.chall_id', '=', 'chall.id');
        }], 'chall.point') // Note: we're directly summing chall.point
            ->where("users.role", "user")
            ->orderBy('total_points', 'desc')
            ->get();

        $rank = $data->search(function ($item) use ($user) {
            return $item->id === $user->id;
        }) + 1; // +1 to convert from 0-indexed to 1-indexed rank


        return response()->json([
            'user' => $user->only(['id', 'username', 'email', 'bio', 'created_at']),
            'rank' => $rank,
            'total_points' => $data[$rank - 1]->total_points,
        ]);
    }
}
