<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\DisputeMessageController;
use App\Http\Controllers\InstitutionAttendedController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PlatfromSettingsController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/reference/{id}', [AdminController::class, 'show'])->name('admin.reference.show');
//    Route::resource('/admin', AdminController::class)->middleware(['auth', 'verified', 'role:admin']);
    Route::patch('reference/{id}/approve', [ReferenceController::class, 'approve'])->name('admin.reference.approve');
    Route::patch('reference/{id}/reject', [ReferenceController::class, 'reject'])->name('admin.reference.reject');
    Route::post('reference/{id}/upload', [ReferenceController::class, 'uploadDocument'])->name('admin.reference.upload');
    Route::get('/settings', [PlatfromSettingsController::class, 'index'])->name('admin.platform.settings');
    Route::patch('/settings', [PlatfromSettingsController::class, 'update'])->name('admin.platform.settings.update');

    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
    Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('admin.user.show');

    Route::get('/user/status/{id}', [AdminController::class, 'approveUser'])->name('admin.user.approve');



});


Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->middleware(['auth', 'verified', 'role:lecturer'])->name('lecturer.dashboard');

Route::resource('/lecturer', LecturerController::class)->middleware(['auth', 'verified']);
Route::resource('/reference', ReferenceController::class)->middleware(['auth', 'verified']);
Route::patch('/lecturer/reference/{id}/approve', [ReferenceController::class, 'approve'])->name('lecturer.reference.approve');
Route::patch('/lecturer/reference/{id}/reject', [ReferenceController::class, 'reject'])->name('lecturer.reference.reject');
Route::patch('reference/{id}/confirm_email_sent', [ReferenceController::class, 'confirm_email_sent'])->name('lecturer.reference.confirm_email_sent');
Route::patch('reference/{id}/confirm_completed', [ReferenceController::class, 'confirm_completed'])->name('lecturer.reference.confirm_completed');
Route::get('/lecturer/reference/{id}', [LecturerController::class, 'show'])->name('lecturer.reference.show');
Route::post('/lecturer/reference/{id}/upload', [ReferenceController::class, 'uploadDocument'])->name('lecturer.reference.upload');
Route::get('/lecturer/institution', [InstitutionAttendedController::class, 'index'])->name('lecturer.institution.index');

Route::get('/student/dashboard', [StudentController::class, 'index'])->middleware(['auth', 'verified', 'role:student'])->name('student.dashboard');

Route::get('/student/reference', [StudentController::class, 'create'])->middleware(['auth', 'verified'])->name('student.reference');
Route::get('/student/reference/{id}/edit', [StudentController::class, 'edit'])->middleware(['auth', 'verified'])->name('student.reference.edit');
Route::get('/student/reference/{id}', [StudentController::class, 'show'])->name('student.reference.show');
Route::get('reference/{id}/confirm_completed', [ReferenceController::class, 'mark_completed'])->name('student.reference.mark_completed');

Route::get('/institution/attended', [InstitutionAttendedController::class, 'index'])->name('institution.attended.show');
Route::post('/institution/attended', [InstitutionAttendedController::class, 'store'])->name('institution.attended.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/institutions-by-state/{stateId?}', [InstitutionController::class, 'getByState'])->name('institutions.by.state');
Route::get('/reference-lecturers-by-state/{stateId}', [LecturerController::class, 'getLecturersForReferenceByState']);
Route::get('/get-lecturers/{institution_id}', [LecturerController::class, 'getLecturersByInstitution']);


//Route::post('/disputes', [DisputeController::class, 'store'])->name('disputes.store'); // Create dispute
//Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show'); // View dispute
//Route::post('/disputes/{dispute}/messages', [DisputeMessageController::class, 'store'])->name('disputes.store'); // Send message
Route::put('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve'])->name('disputes.resolve'); // Resolve dispute
Route::put('/disputes/{dispute}/open', [DisputeController::class, 'open'])->name('disputes.open'); // Resolve dispute

Route::middleware(['auth'])->group(function () {
    Route::get('/disputes', [DisputeController::class, 'index'])->name('disputes.index'); // List disputes
    Route::get('/disputes/create/{reference_id}', [DisputeController::class, 'create'])->name('disputes.create'); // Create dispute
    Route::post('/disputes', [DisputeController::class, 'store'])->name('disputes.store'); // Store dispute
    Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show'); // Show dispute details
    Route::post('/disputes/{dispute}/messages', [DisputeMessageController::class, 'store'])->name('disputes.messages.send'); // Send message

    // Notification routes
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

require __DIR__.'/auth.php';
