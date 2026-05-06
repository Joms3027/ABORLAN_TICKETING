<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->withCount('bookings')->latest('id');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'q'     => $search,
        ]);
    }

    public function show(User $user): View
    {
        $user->load(['bookings' => fn ($q) => $q->latest('id')]);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['user' => 'You cannot change your own admin status.']);
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        return back()->with('status', $user->name.' is now '.($user->is_admin ? 'an administrator' : 'a regular user').'.');
    }
}
