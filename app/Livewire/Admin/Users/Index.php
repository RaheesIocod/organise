<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        $users = User::with('designation')->paginate(10);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }
}
