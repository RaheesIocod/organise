<?php

namespace App\Livewire\Admin\LeaveTypes;

use App\Models\LeaveType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class Index extends Component
{
    use WithPagination;

    #[Title('Leave Types')]

    public $search = '';
    public $showDeleteModal = false;
    public $leaveTypeIdToDelete;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->leaveTypeIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteLeaveType()
    {
        $leaveType = LeaveType::findOrFail($this->leaveTypeIdToDelete);

        // Check if there are any leave applications with this leave type
        if ($leaveType->leaveApplications()->count() > 0) {
            session()->flash('error', 'Cannot delete this leave type because it is used in leave applications.');
        } else {
            $leaveType->delete();
            session()->flash('message', 'Leave type deleted successfully!');
        }

        $this->showDeleteModal = false;
        $this->leaveTypeIdToDelete = null;
    }

    public function render()
    {
        return view('livewire.admin.leave-types.index', [
            'leaveTypes' => LeaveType::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
