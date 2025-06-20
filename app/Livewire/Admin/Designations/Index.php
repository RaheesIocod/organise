<?php

namespace App\Livewire\Admin\Designations;

use App\Models\Designation;
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
        $designations = Designation::paginate(10);

        return view('livewire.admin.designations.index', [
            'designations' => $designations,
        ]);
    }
}
