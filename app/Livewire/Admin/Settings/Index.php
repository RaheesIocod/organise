<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();

        // Check if user has permission
        if (! $this->user->hasRole('admin')) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.index');
    }
}
