<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    public User $user;
    public $name;
    public $username;
    public $email;
    public $number;
    public $password;
    public $password_confirmation;
    public $role;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->number = $user->number;
        $this->role = $user->getRoleNames()->first() ?? 'customer';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:40',
            'username' => 'required|string|max:40|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'number' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|string|in:customer,seller,support,admin',
        ];
    }

    public function render()
    {
        $roles = Role::all()->pluck('name');

        return view('livewire.user-edit', [
            'roles' => $roles,
        ]);
    }

    public function update()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'number' => $this->number,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $this->user->update($userData);
        $this->user->syncRoles($this->role);

        $this->dispatch('userUpdated');

        session()->flash('message', 'User updated successfully.');
        return redirect()->route('users.index');
    }
}
