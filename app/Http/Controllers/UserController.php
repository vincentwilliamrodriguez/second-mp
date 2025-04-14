<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }


    public function create() {
        $roles = Role::all()->pluck('name');
        return view('users.create', compact('roles'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'username' => 'required|string|max:40|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email',
            'number' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'password' => 'required|min:8|confirmed',
            'role' => 'required|string|in:customer,seller,support,admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'number' => $validated['number'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('message', 'User created successfully.');
    }

    public function edit(User $user) {
        $roles = Role::all()->pluck('name');
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'username' => 'required|string|max:40|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'number' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|string|in:customer,seller,support,admin',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'number' => $validated['number'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        $user->syncRoles($validated['role']);

        return redirect()->route('users.index')
            ->with('message', 'User updated successfully.');
    }


    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('users.index')
            ->with('message', 'User removed successfully.');
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
    }
}
