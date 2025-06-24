<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $user;

    public $employee;

    public $selectedTab = 'profile';

    public function mount(User $user)
    {
        $this->user = Auth::user();
        $this->employee = $user;
    }

    public function render()
    {
        return view('livewire.employees.show');
    }

    public function selectTab($tab)
    {
        $this->selectedTab = $tab;
    }
}
