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
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Verification routes - These should be accessible without verification
Route::middleware(['auth'])->group(function () {
    Route::get('/verification/required', [VerificationController::class, 'showVerificationRequired'])->name('verification.required');
    Route::post('/verification/submit', [VerificationController::class, 'submit'])->name('verification.submit');
});

// All other routes should require verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Student routes
    Route::get('/student/dashboard', [StudentController::class, 'index'])->middleware(['role:student'])->name('student.dashboard');
    Route::get('/student/reference', [StudentController::class, 'reference'])->name('student.reference');
    Route::post('/reference', [ReferenceController::class, 'store'])->name('reference.store');
    Route::get('/student/reference/{id}/edit', [ReferenceController::class, 'edit'])->name('student.reference.edit');
    Route::put('/student/reference/{id}', [ReferenceController::class, 'update'])->name('student.reference.update');
    Route::get('/student/reference/{id}', [StudentController::class, 'show'])->name('student.reference.show');
    Route::get('reference/{id}/confirm_completed', [ReferenceController::class, 'mark_completed'])->name('student.reference.mark_completed');

    // Lecturer routes
    Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->middleware(['role:lecturer'])->name('lecturer.dashboard');
    Route::resource('/lecturer', LecturerController::class);
    Route::resource('/reference', ReferenceController::class);
    Route::patch('/lecturer/reference/{id}/approve', [ReferenceController::class, 'approve'])->name('lecturer.reference.approve');
    Route::patch('/lecturer/reference/{id}/reject', [ReferenceController::class, 'reject'])->name('lecturer.reference.reject');
    Route::patch('reference/{id}/confirm_email_sent', [ReferenceController::class, 'confirm_email_sent'])->name('lecturer.reference.confirm_email_sent');
    Route::patch('reference/{id}/confirm_completed', [ReferenceController::class, 'confirm_completed'])->name('lecturer.reference.confirm_completed');
    Route::get('/lecturer/reference/{id}', [LecturerController::class, 'show'])->name('lecturer.reference.show');
    Route::post('/lecturer/reference/{id}/upload', [ReferenceController::class, 'uploadDocument'])->name('lecturer.reference.upload');
    Route::get('/lecturer/institution', [InstitutionAttendedController::class, 'index'])->name('lecturer.institution.index');

    // Institution routes
    Route::get('/institution/attended', [InstitutionAttendedController::class, 'show'])->name('institution.attended.show');
    Route::post('/institution/attended', [InstitutionAttendedController::class, 'store'])->name('institution.attended.store');

    // Dispute routes
    Route::get('/disputes', [DisputeController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/create/{reference_id}', [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/disputes', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{dispute}/messages', [DisputeMessageController::class, 'store'])->name('disputes.messages.send');
    Route::put('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::put('/disputes/{dispute}/open', [DisputeController::class, 'open'])->name('disputes.open');

    // Notification routes
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/fund', [WalletController::class, 'initializePayment'])->name('wallet.fund');
    Route::get('/payment/callback', [WalletController::class, 'handleCallback'])->name('payment.callback');
});

// Admin routes - These require both verification and admin role
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/reference/{id}', [AdminController::class, 'show'])->name('admin.reference.show');
    Route::patch('reference/{id}/approve', [ReferenceController::class, 'approve'])->name('admin.reference.approve');
    Route::patch('reference/{id}/reject', [ReferenceController::class, 'reject'])->name('admin.reference.reject');
    Route::post('reference/{id}/upload', [ReferenceController::class, 'uploadDocument'])->name('admin.reference.upload');
    Route::get('/settings', [PlatfromSettingsController::class, 'index'])->name('admin.platform.settings');
    Route::patch('/settings', [PlatfromSettingsController::class, 'update'])->name('admin.platform.settings.update');
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
    Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('admin.user.show');
    Route::get('/user/status/{id}', [AdminController::class, 'approveUser'])->name('admin.user.approve');
    Route::get('/verification-requests', [AdminController::class, 'verificationRequests'])->name('admin.verification.requests');
    Route::patch('/verification/{verificationRequest}/approve', [VerificationController::class, 'approve'])->name('admin.verification.approve');
    Route::patch('/verification/{verificationRequest}/reject', [VerificationController::class, 'reject'])->name('admin.verification.reject');
});

// API routes for dynamic data
Route::get('/institutions-by-state/{stateId?}', [InstitutionController::class, 'getByState'])->name('institutions.by.state');
Route::get('/reference-lecturers-by-state/{stateId}', [LecturerController::class, 'getLecturersForReferenceByState']);
Route::get('/get-lecturers/{institution_id}', [LecturerController::class, 'getLecturersByInstitution']);

require __DIR__.'/auth.php';
