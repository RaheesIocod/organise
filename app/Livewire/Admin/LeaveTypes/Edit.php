<?php

namespace App\Livewire\Admin\LeaveTypes;

use App\Models\LeaveType;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Title('Edit Leave Type')]
    public LeaveType $leaveType;

    public $name;

    public $description;

    public $days_allowed;

    public $requires_approval;

    public $is_paid;

    public $color;

    public function mount(LeaveType $leaveType)
    {
        $this->leaveType = $leaveType;
        $this->name = $leaveType->name;
        $this->description = $leaveType->description;
        $this->days_allowed = $leaveType->days_allowed;
        $this->requires_approval = $leaveType->requires_approval;
        $this->is_paid = $leaveType->is_paid;
        $this->color = $leaveType->color;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:leave_types,name,'.$this->leaveType->id,
            'description' => 'nullable|string|max:255',
            'days_allowed' => 'required|integer|min:0',
            'requires_approval' => 'boolean',
            'is_paid' => 'boolean',
            'color' => 'required|string|max:7',
        ];
    }

    public function update()
    {
        $validatedData = $this->validate();

        $this->leaveType->update($validatedData);

        session()->flash('message', 'Leave type updated successfully!');

        return $this->redirect(route('admin.leave-types'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.leave-types.edit');
    }
}
