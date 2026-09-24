<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('manage.users', [
            'users' => User::query()->orderBy('username')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'role' => ['required', 'in:admin,yunita'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create($data);

        return redirect()->route('manage.users.index')->with('success', __('flash.user_created'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'role' => ['required', 'in:admin,yunita'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->with('success', __('flash.user_self_role'));
        }

        $user->update($data);

        return redirect()->route('manage.users.index')->with('success', __('flash.user_updated'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('success', __('flash.user_self_delete'));
        }

        $user->delete();

        return redirect()->route('manage.users.index')->with('success', __('flash.user_deleted'));
    }
}
