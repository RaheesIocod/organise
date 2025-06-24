<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $user;

    public $userToEdit;

    public function mount(User $user)
    {
        $this->user = Auth::user();
        $this->userToEdit = $user;
    }

    public function render()
    {
        return view('livewire.admin.users.edit');
    }
}
