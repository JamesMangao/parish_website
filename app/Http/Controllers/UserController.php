<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in(['super_admin', 'staff', 'soccom'])],
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        LogService::log('create_user', null, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'staff', 'soccom'])],
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        $newActive = $request->boolean('is_active');

        if ($this->wouldRemoveLastActiveSuperAdmin($user, $validated['role'], $newActive)) {
            return back()->with('error', 'You cannot change this account: it is the only active super administrator.');
        }

        $oldRole = $user->role;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $newActive;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        LogService::log('update_user', $user, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'old_role' => $oldRole,
            'password_changed' => ! empty($validated['password'] ?? null),
        ]);

        if ($oldRole !== $validated['role']) {
            LogService::log('role_change', $user, [
                'old_role' => $oldRole,
                'new_role' => $validated['role'],
            ]);
        }

        return back()->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        if ($this->wouldRemoveLastActiveSuperAdmin($user, $user->role, false)) {
            return back()->with('error', 'You cannot deactivate this account: it is the only active super administrator.');
        }

        $user->update(['is_active' => false]);
        LogService::log('deactivate_user', $user, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return back()->with('success', 'User deactivated successfully!');
    }

    /**
     * Prevent removing the last active super administrator, which would
     * permanently lock everyone out of the admin panel.
     */
    private function wouldRemoveLastActiveSuperAdmin(User $user, string $newRole, bool $newActive): bool
    {
        if ($user->role !== 'super_admin' || ! $user->is_active) {
            return false;
        }

        $activeSuperAdmins = User::where('role', 'super_admin')
            ->where('is_active', true)
            ->count();

        return $activeSuperAdmins <= 1 && ($newRole !== 'super_admin' || ! $newActive);
    }
}
