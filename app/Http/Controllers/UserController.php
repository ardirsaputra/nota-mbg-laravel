<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nota;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Get all users with their notas
        $users = User::where('role', 'user')
            ->with(['notas' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate stats for each user
        foreach ($users as $user) {
            $user->total_notas = $user->notas->count();
            $user->total_value = $user->notas->sum('total');
        }

        return view('users.index', compact('users'));
    }
}
