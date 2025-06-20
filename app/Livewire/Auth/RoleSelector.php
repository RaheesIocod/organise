<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class RoleSelector extends Component
{
    public $selectedRole = '';
    public $demoCredentials = [];

    // Event that will be emitted to the parent component
    public function updatedSelectedRole()
    {
        if (!$this->selectedRole) {
            $this->demoCredentials = [];
            return;
        }

        // Set credentials based on role
        switch ($this->selectedRole) {
            case 'admin':
                $this->demoCredentials = [
                    'email' => 'admin@iocod.com',
                    'password' => 'password'
                ];
                break;
            case 'hr':
                $this->demoCredentials = [
                    'email' => 'hr@iocod.com',
                    'password' => 'password'
                ];
                break;
            case 'manager':
                $this->demoCredentials = [
                    'email' => 'manager@iocod.com',
                    'password' => 'password'
                ];
                break;
            case 'employee':
                $this->demoCredentials = [
                    'email' => 'employee1@iocod.com',
                    'password' => 'password'
                ];
                break;
            default:
                $this->demoCredentials = [];
                break;
        }

        // Emit event with credentials
        $this->dispatch('credentials-selected', $this->demoCredentials);
    }

    public function fillCredentials()
    {
        if ($this->selectedRole) {
            $this->updatedSelectedRole();
        }
    }

    public function render()
    {
        return view('livewire.auth.role-selector');
    }
}
