<?php

namespace App\Livewire\Admin\LeaveTypes;

use App\Models\LeaveType;
use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Title('Create Leave Type')]
    public $name;
    public $description;
    public $days_allowed = 0;
    public $requires_approval = true;
    public $is_paid = true;
    public $color = '#4F46E5'; // Default color (Indigo)

    protected $rules = [
        'name' => 'required|string|max:100|unique:leave_types,name',
        'description' => 'nullable|string|max:255',
        'days_allowed' => 'required|integer|min:0',
        'requires_approval' => 'boolean',
        'is_paid' => 'boolean',
        'color' => 'required|string|max:7',
    ];

    public function save()
    {
        $validatedData = $this->validate();

        LeaveType::create($validatedData);

        session()->flash('message', 'Leave type created successfully!');

        return $this->redirect(route('admin.leave-types'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.leave-types.create');
    }
}
