<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Leave Management
    Route::get('/leaves', \App\Livewire\Leaves\Index::class)->name('leaves');
    Route::get('/leaves/create', \App\Livewire\Leaves\Create::class)->name('leaves.create');
    Route::get('/leaves/{leave}', \App\Livewire\Leaves\Show::class)->name('leaves.show');

    // Attendance
    Route::get('/attendance', \App\Livewire\Attendance\Index::class)->name('attendance');
    Route::get('/attendance/calendar', \App\Livewire\Attendance\Calendar::class)->name('attendance.calendar');
    Route::get('/attendance/check-in-out', \App\Livewire\Attendance\CheckInOut::class)->name('attendance.check-in-out');
    Route::get('/attendance/calendar', \App\Livewire\Attendance\Calendar::class)->name('attendance.calendar');
    Route::get('/attendance/check-in-out', \App\Livewire\Attendance\CheckInOut::class)->name('attendance.check-in-out');

    // Projects
    Route::get('/projects', \App\Livewire\Projects\Index::class)->name('projects');
    Route::get('/projects/{project}', \App\Livewire\Projects\Show::class)->name('projects.show');

    // Tasks
    Route::get('/tasks', \App\Livewire\Tasks\Index::class)->name('task-time-entries');

    // Reports
    Route::get('/reports', \App\Livewire\Reports\Index::class)->name('reports');
    Route::get('/reports/attendance', \App\Livewire\Reports\Attendance::class)->name('reports.attendance');
    Route::get('/reports/leaves', \App\Livewire\Reports\Leaves::class)->name('reports.leaves');
    Route::get('/reports/tasks', \App\Livewire\Reports\Tasks::class)->name('reports.tasks');

    // Holidays
    Route::get('/holidays', \App\Livewire\Holidays\Index::class)->name('holidays');

    // Profile
    Route::get('/profile', \App\Livewire\Profile\Index::class)->name('profile');

    // Manager Routes
    Route::middleware(['role:manager|admin'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Manager\Dashboard::class)->name('dashboard');
        Route::get('/team', \App\Livewire\Manager\Team\Index::class)->name('team');
        Route::get('/team/{user}', \App\Livewire\Manager\Team\Show::class)->name('team.show');
        Route::get('/leave-approvals', \App\Livewire\Manager\LeaveApprovals\Index::class)->name('leave-approvals');
        Route::get('/team-attendance', \App\Livewire\Manager\TeamAttendance::class)->name('team-attendance');
        Route::get('/team-tasks', \App\Livewire\Manager\TeamTasks::class)->name('team-tasks');
    });

    // Leave Approvals
    Route::get('/leave-approvals/{leaveApplication}', \App\Livewire\LeaveApprovals\Show::class)->name('leave-approvals.show');

    // Employee Directory
    Route::get('/employees', \App\Livewire\Employees\Index::class)->name('employees');
    Route::get('/employees/{user}', \App\Livewire\Employees\Show::class)->name('employees.show');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Settings
        Route::get('/settings', \App\Livewire\Admin\Settings\Index::class)->name('settings');

        // Users
        Route::get('/users', \App\Livewire\Admin\Users\Index::class)->name('users');
        Route::get('/users/create', \App\Livewire\Admin\Users\Create::class)->name('users.create');
        Route::get('/users/{user}', \App\Livewire\Admin\Users\Show::class)->name('users.show');
        Route::get('/users/{user}/edit', \App\Livewire\Admin\Users\Edit::class)->name('users.edit');

        // Designations
        Route::get('/designations', \App\Livewire\Admin\Designations\Index::class)->name('designations');
        Route::get('/designations/create', \App\Livewire\Admin\Designations\Create::class)->name('designations.create');
        Route::get('/designations/{designation}/edit', \App\Livewire\Admin\Designations\Edit::class)->name('designations.edit');

        // Leave Types
        Route::get('/leave-types', \App\Livewire\Admin\LeaveTypes\Index::class)->name('leave-types');
        Route::get('/leave-types/create', \App\Livewire\Admin\LeaveTypes\Create::class)->name('leave-types.create');
        Route::get('/leave-types/{leaveType}/edit', \App\Livewire\Admin\LeaveTypes\Edit::class)->name('leave-types.edit');
    });
});
