<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $user;

    public $userToShow;

    public function mount(User $user)
    {
        $this->user = Auth::user();
        $this->userToShow = $user;
    }

    public function render()
    {
        return view('livewire.admin.users.show');
    }
}
