<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\DisputeDocumentController;
use App\Http\Controllers\DisputeMessageController;
use App\Http\Controllers\InstitutionAttendedController;
use App\Http\Controllers\InstitutionEmailVerificationController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PlatfromSettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Affiliate routes (public)
Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
Route::post('/affiliate/apply', [AffiliateController::class, 'store'])->name('affiliate.store');

// Referral tracking route (public)
Route::get('/ref/{code}', [ReferralController::class, 'trackClick'])->name('referral.track');

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
    Route::get('/student/references', [StudentController::class, 'references'])->name('student.references');
    Route::post('/reference', [ReferenceController::class, 'store'])->name('reference.store');
    Route::get('/student/reference/{id}/edit', [ReferenceController::class, 'edit'])->name('student.reference.edit');
    Route::put('/student/reference/{id}', [ReferenceController::class, 'update'])->name('student.reference.update');
    Route::get('/student/reference/{id}', [StudentController::class, 'show'])->name('student.reference.show');
    Route::post('/student/reference/{id}/message', [ReferenceController::class, 'sendStudentMessage'])->name('student.reference.message');
    Route::get('reference/{id}/confirm_completed', [ReferenceController::class, 'mark_completed'])->name('student.reference.mark_completed');

    // Lecturer routes
    Route::get('/lecturer/dashboard', [LecturerController::class, 'index'])->middleware(['role:lecturer'])->name('lecturer.dashboard');
    Route::get('/lecturer/references', [LecturerController::class, 'references'])->name('lecturer.references');
    
    // Withdrawal routes (moved before resource route to avoid conflicts)
    Route::get('/lecturer/withdrawal', [WithdrawalController::class, 'create'])->middleware(['auth'])->name('lecturer.withdrawal.create');
    Route::post('/lecturer/withdrawal', [WithdrawalController::class, 'store'])->middleware(['auth'])->name('lecturer.withdrawal.store');
    Route::get('/lecturer/withdrawal/history', [WithdrawalController::class, 'index'])->middleware(['auth'])->name('lecturer.withdrawal.history');
    
    Route::resource('/lecturer', LecturerController::class);
    Route::resource('/reference', ReferenceController::class);
    Route::patch('/lecturer/reference/{id}/approve', [ReferenceController::class, 'approve'])->name('lecturer.reference.approve');
    Route::patch('/lecturer/reference/{id}/reject', [ReferenceController::class, 'reject'])->name('lecturer.reference.reject');
    Route::patch('reference/{id}/confirm_email_sent', [ReferenceController::class, 'confirm_email_sent'])->name('lecturer.reference.confirm_email_sent');
    Route::patch('reference/{id}/confirm_completed', [ReferenceController::class, 'confirm_completed'])->name('lecturer.reference.confirm_completed');
    Route::get('/lecturer/reference/{id}', [LecturerController::class, 'show'])->name('lecturer.reference.show');
    Route::post('/lecturer/reference/{id}/upload', [ReferenceController::class, 'upload'])->name('lecturer.reference.upload');
    Route::post('/lecturer/reference/{id}/message', [ReferenceController::class, 'sendMessage'])->name('lecturer.reference.message');
    Route::get('/lecturer/institution', [InstitutionAttendedController::class, 'index'])->name('lecturer.institution.index');
    
    // Test withdrawal route (temporary - remove after testing)
    Route::get('/test-withdrawal', [WithdrawalController::class, 'create'])->middleware(['auth'])->name('test.withdrawal');
    Route::get('/test-withdrawal-history', [WithdrawalController::class, 'index'])->middleware(['auth'])->name('test.withdrawal.history');
    
    // Debug route to check user role (temporary)
    Route::get('/debug-user-role', function() {
        return response()->json([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'no role',
            'user_email' => auth()->user()->email ?? 'no email',
            'is_authenticated' => auth()->check()
        ]);
    })->middleware(['auth'])->name('debug.user.role');
    
    // Simple test route to verify URL structure
    Route::get('/test-route-works', function() {
        return '<h1>Route Works!</h1><p>If you can see this, the route structure is working correctly.</p>';
    })->name('test.route.works');

    // Institution routes
    Route::get('/institution/attended', [InstitutionAttendedController::class, 'show'])->name('institution.attended.show');
    Route::get('/institution/attended/index', [InstitutionAttendedController::class, 'index'])->name('institution.attended.index');
    Route::get('/institution/attended/create', [InstitutionAttendedController::class, 'create'])->name('institution.attended.create');
    Route::get('/institution/attended/list', [InstitutionAttendedController::class, 'list'])->name('institution.attended.list');
    Route::post('/institution/attended', [InstitutionAttendedController::class, 'store'])->name('institution.attended.store');
    Route::get('/institution/attended/{institutionAttended}/edit', [InstitutionAttendedController::class, 'edit'])->name('institution.attended.edit');
    Route::put('/institution/attended/{institutionAttended}', [InstitutionAttendedController::class, 'update'])->name('institution.attended.update');
    Route::delete('/institution/attended/{institutionAttended}', [InstitutionAttendedController::class, 'destroy'])->name('institution.attended.destroy');
    Route::delete('/institution/attended/{institutionAttended}/document/{document}', [InstitutionAttendedController::class, 'deleteDocument'])->name('institution.attended.document.delete');
    Route::post('/institution/attended/{institutionAttended}/verification/send', [InstitutionAttendedController::class, 'sendVerificationEmail'])->name('institution.verification.send');
    Route::get('/institution/attended/{institution}/verify/{token}', [InstitutionAttendedController::class, 'verifyEmail'])->name('institution.email.verify');
    
    // Institution email verification routes
    Route::post('/institution/{institution}/verification/send', [InstitutionEmailVerificationController::class, 'sendVerificationEmail'])->name('institution.verification.send');
    Route::get('/institution/verify/{token}', [InstitutionEmailVerificationController::class, 'verify'])->name('institution.verification.verify');

    // Dispute routes
    Route::get('/disputes', [DisputeController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/create/{reference_id}', [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/disputes', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{dispute}/messages', [DisputeMessageController::class, 'store'])->name('disputes.messages.send');
    Route::put('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::put('/disputes/{dispute}/open', [DisputeController::class, 'open'])->name('disputes.open');
    
    // Dispute document routes
    Route::post('/disputes/{dispute}/documents', [DisputeDocumentController::class, 'store'])->name('disputes.documents.store');
    Route::get('/disputes/documents/{document}/download', [DisputeDocumentController::class, 'download'])->name('disputes.documents.download');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Wallet routes
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/fund', [WalletController::class, 'initializePayment'])->name('wallet.fund');
    Route::get('/payment/callback', [WalletController::class, 'handleCallback'])->name('payment.callback');

    // Referral routes
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::post('/referrals/generate', [ReferralController::class, 'generateCode'])->name('referral.generate');
    Route::get('/referrals/statistics', [ReferralController::class, 'statistics'])->name('referrals.statistics');
});

// Admin routes - These require both verification and admin role
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // User management routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show'])->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    
    Route::get('/references', [AdminController::class, 'allReferences'])->name('admin.references.all');
    Route::get('/reference/{id}', [AdminController::class, 'show'])->name('admin.reference.show');
    Route::patch('reference/{id}/approve', [ReferenceController::class, 'approve'])->name('admin.reference.approve');
    Route::patch('reference/{id}/reject', [ReferenceController::class, 'reject'])->name('admin.reference.reject');
    Route::post('reference/{id}/upload', [ReferenceController::class, 'upload'])->name('admin.reference.upload');
    Route::get('/settings', [PlatfromSettingsController::class, 'index'])->name('admin.platform.settings');
    Route::match(['POST', 'PATCH'], '/settings', [PlatfromSettingsController::class, 'update'])->name('admin.platform.settings.update');
    
    // Institution management
    Route::get('/institutions', [AdminController::class, 'institutions'])->name('admin.institutions.index');
    Route::post('/institutions', [AdminController::class, 'storeInstitution'])->name('admin.institutions.store');
    Route::put('/institutions/{institution}', [AdminController::class, 'updateInstitution'])->name('admin.institutions.update');
    Route::delete('/institutions/{institution}', [AdminController::class, 'destroyInstitution'])->name('admin.institutions.destroy');
    
    // Course management
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['show'])->names([
        'index' => 'admin.courses.index',
        'create' => 'admin.courses.create',
        'store' => 'admin.courses.store',
        'edit' => 'admin.courses.edit',
        'update' => 'admin.courses.update',
        'destroy' => 'admin.courses.destroy',
    ]);
    Route::get('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'show'])->name('admin.courses.show');
    
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
    Route::get('/user/{id}', [AdminController::class, 'showUser'])->name('admin.user.show');
    Route::get('/user/status/{id}', [AdminController::class, 'approveUser'])->name('admin.user.approve');
    
    // Export routes
    Route::get('/students/export', [AdminController::class, 'exportStudents'])->name('admin.students.export');
    Route::get('/lecturers/export', [AdminController::class, 'exportLecturers'])->name('admin.lecturers.export');
    Route::get('/references/export', [AdminController::class, 'exportReferences'])->name('admin.references.export');
    Route::get('/verification-requests', [AdminController::class, 'verificationRequests'])->name('admin.verification.requests');
    Route::patch('/verification/{verificationRequest}/approve', [VerificationController::class, 'approve'])->name('admin.verification.approve');
    Route::patch('/verification/{verificationRequest}/reject', [VerificationController::class, 'reject'])->name('admin.verification.reject');
    
    // Affiliate management routes
    Route::get('/affiliates', [AffiliateController::class, 'adminIndex'])->name('admin.affiliates.index');
    Route::get('/affiliates/{id}', [AffiliateController::class, 'show'])->name('admin.affiliates.show');
    Route::post('/affiliates/{id}/approve', [AffiliateController::class, 'approve'])->name('admin.affiliates.approve');
    Route::post('/affiliates/{id}/reject', [AffiliateController::class, 'reject'])->name('admin.affiliates.reject');
    Route::delete('/affiliates/{id}', [AffiliateController::class, 'destroy'])->name('admin.affiliates.destroy');
});

// API routes for dynamic data
Route::get('/institutions-by-state/{stateId?}', [InstitutionController::class, 'getByState'])->name('institutions.by.state');
Route::get('/reference-lecturers-by-state/{stateId}', [LecturerController::class, 'getLecturersForReferenceByState']);
Route::get('/get-lecturers/{institution_id}', [LecturerController::class, 'getLecturersByInstitution']);

require __DIR__.'/auth.php';
