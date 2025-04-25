<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    public $name;
    public $username;
    public $email;
    public $number;
    public $password;
    public $password_confirmation;
    public $role = 'customer';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:40',
            'username' => 'required|string|max:40|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email',
            'number' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'password' => 'required|min:8|confirmed',
            'role' => 'required|string|in:customer,seller,support,admin',
        ];
    }

    public function render()
    {
        $roles = Role::all()->pluck('name');

        return view('livewire.user-create', [
            'roles' => $roles,
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'username', 'email', 'number', 'password', 'password_confirmation', 'role']);
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'number' => $this->number,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->resetForm();

        $this->dispatch('userCreated');

        session()->flash('message', 'User created successfully.');
        return redirect()->route('users.index');
    }
}
