<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupItemController;
use App\Http\Controllers\BackupRequestController;
use App\Http\Controllers\DailyTrafficReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueReportController;
use App\Http\Controllers\ManageReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParkingLocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportRecapController;
use App\Http\Controllers\TechnicianReportController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CS by AI
    |--------------------------------------------------------------------------
    | Route ini digunakan untuk fitur Customer Service berbasis AI.
    | Hanya user login yang dapat mengakses agar penggunaan API tetap aman.
    */
    Route::post('/cs-ai/chat', [AiChatController::class, 'chat'])->name('cs-ai.chat');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications-unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications-latest', [NotificationController::class, 'latest'])->name('notifications.latest');

    /*
    |--------------------------------------------------------------------------
    | Backup Requests
    |--------------------------------------------------------------------------
    */
    Route::resource('backup-requests', BackupRequestController::class);
    Route::post('/backup-requests/{backupRequest}/approve', [BackupRequestController::class, 'approve'])->name('backup-requests.approve');
    Route::post('/backup-requests/{backupRequest}/reject', [BackupRequestController::class, 'reject'])->name('backup-requests.reject');
    Route::post('/backup-requests/{backupRequest}/process', [BackupRequestController::class, 'process'])->name('backup-requests.process');
    Route::post('/backup-requests/{backupRequest}/complete', [BackupRequestController::class, 'complete'])->name('backup-requests.complete');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,manajer')->group(function () {
        Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management.index');

        Route::middleware('role:admin')->group(function () {
            Route::get('/user-management/create', [UserManagementController::class, 'create'])->name('user-management.create');
            Route::post('/user-management', [UserManagementController::class, 'store'])->name('user-management.store');
            Route::get('/user-management/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
            Route::put('/user-management/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
            Route::delete('/user-management/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');
            Route::post('/user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
            Route::post('/user-management/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('user-management.toggle-status');
        });

        Route::get('/user-management/{user}', [UserManagementController::class, 'show'])->name('user-management.show');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Petugas Parkir Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/issue-reports', [IssueReportController::class, 'index'])->name('issue-reports.index');
    Route::get('/issue-reports/create', [IssueReportController::class, 'create'])->name('issue-reports.create');
    Route::post('/issue-reports', [IssueReportController::class, 'store'])->name('issue-reports.store');
    Route::get('/issue-reports/{issueReport}', [IssueReportController::class, 'show'])->name('issue-reports.show');

    /*
    |--------------------------------------------------------------------------
    | Traffic Harian - Petugas
    |--------------------------------------------------------------------------
    | Petugas boleh list, create, store, edit, update, delete.
    | Route show dipisah agar Manajer bisa membuka detail dari Laporan Rekap.
    */
    Route::get('/traffic-reports', [DailyTrafficReportController::class, 'index'])->name('traffic-reports.index');
    Route::get('/traffic-reports/create', [DailyTrafficReportController::class, 'create'])->name('traffic-reports.create');
    Route::post('/traffic-reports', [DailyTrafficReportController::class, 'store'])->name('traffic-reports.store');
    Route::get('/traffic-reports/{trafficReport}/edit', [DailyTrafficReportController::class, 'edit'])->name('traffic-reports.edit');
    Route::put('/traffic-reports/{trafficReport}', [DailyTrafficReportController::class, 'update'])->name('traffic-reports.update');
    Route::delete('/traffic-reports/{trafficReport}', [DailyTrafficReportController::class, 'destroy'])->name('traffic-reports.destroy');
});

/*
|--------------------------------------------------------------------------
| Traffic Detail Routes
|--------------------------------------------------------------------------
| Detail traffic bisa dibuka oleh:
| - Petugas: hanya jika lokasi operasional sama
| - Manajer: dari laporan rekap
|
| Admin Operasional tidak boleh akses detail traffic rekap.
*/
Route::middleware(['auth', 'role:petugas,manajer'])->group(function () {
    Route::get('/traffic-reports/{trafficReport}', [DailyTrafficReportController::class, 'show'])->name('traffic-reports.show');
});

/*
|--------------------------------------------------------------------------
| Teknisi Vendor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/technician-reports', [TechnicianReportController::class, 'index'])->name('technician-reports.index');
    Route::get('/technician-reports/{issueReport}', [TechnicianReportController::class, 'show'])->name('technician-reports.show');
    Route::post('/technician-reports/{issueReport}/update-status', [TechnicianReportController::class, 'updateStatus'])->name('technician-reports.update-status');
});

/*
|--------------------------------------------------------------------------
| Manajer Operasional Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:manajer'])->group(function () {
    Route::get('/manage-reports', [ManageReportController::class, 'index'])->name('manage-reports.index');
    Route::get('/manage-reports/{issueReport}', [ManageReportController::class, 'show'])->name('manage-reports.show');
    Route::post('/manage-reports/{issueReport}/verify-assign', [ManageReportController::class, 'verifyAndAssign'])->name('manage-reports.verify-assign');
    Route::post('/manage-reports/{issueReport}/reject', [ManageReportController::class, 'reject'])->name('manage-reports.reject');
    Route::post('/manage-reports/{issueReport}/close', [ManageReportController::class, 'close'])->name('manage-reports.close');

    /*
    |--------------------------------------------------------------------------
    | Laporan Rekap - Khusus Manajer Operasional
    |--------------------------------------------------------------------------
    */
    Route::get('/report-recaps', [ReportRecapController::class, 'index'])->name('report-recaps.index');
    Route::get('/report-recaps/export/issue-reports', [ReportRecapController::class, 'exportIssueReports'])->name('report-recaps.export.issue-reports');
    Route::get('/report-recaps/export/traffic-reports', [ReportRecapController::class, 'exportTrafficReports'])->name('report-recaps.export.traffic-reports');
    Route::get('/report-recaps/export/backup-requests', [ReportRecapController::class, 'exportBackupRequests'])->name('report-recaps.export.backup-requests');
});

/*
|--------------------------------------------------------------------------
| Admin Operasional Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('parking-locations', ParkingLocationController::class);
    Route::resource('backup-items', BackupItemController::class);
});