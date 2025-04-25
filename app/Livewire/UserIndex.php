<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    // Refresh the component when a user is created or deleted
    protected $listeners = ['userCreated' => '$refresh', 'userDeleted' => '$refresh'];

    public function render()
    {
        $users = User::all();

        return view('livewire.user-index', [
            'users' => $users,
        ]);
    }

    public function getUsersTableWidths()
    {
        return [
            'Name' => '120px',
            'Username' => '120px',
            'Email' => '120px',
            'Number' => '120px',
        ];
    }

    public function getUsersTableColumns()
    {
        return [
            'Name' => function($user) {
                return "<a class='hover:underline' href='" . route('users.show', $user) . "'>" . $user->name . "</a>";
            },
            'Username' => function($user) {
                return $user->username;
            },
            'Email' => function($user) {
                return $user->email;
            },
            'Number' => function($user) {
                return $user->number;
            },
            'Role' => function($user) {
                $html = "<div class='flex justify-center'>";

                foreach($user->getRoleNames() as $role) {
                    $html .= "<span class='px-2
                     py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800'>{$role}</span>";
                }

                $html .= "</div>";

                return $html;
            },
            'Actions' => function($user) {
                return view('components.user-actions', compact('user'))->render();
            }
        ];
    }
}
