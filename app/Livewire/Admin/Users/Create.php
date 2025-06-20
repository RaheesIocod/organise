<?php

namespace App\Livewire\Admin\Users;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
