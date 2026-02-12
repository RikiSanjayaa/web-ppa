<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FaqController;

use App\Http\Controllers\Admin\HotlineAccessController as AdminHotlineAccessController;
use App\Http\Controllers\Admin\LeaderController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\LocationMonitoringController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HotlineAccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/organisasi', [PublicPageController::class, 'organisasi'])->name('organisasi');
Route::get('/layanan-masyarakat', [PublicPageController::class, 'layananMasyarakat'])->name('layanan-masyarakat');
Route::post('/aduan', [ComplaintController::class, 'store'])
    ->middleware('throttle:complaints')
    ->name('complaints.store');
Route::post('/konsultasi', [ConsultationController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('consultations.store');
Route::post('/api/hotline-access', [HotlineAccessController::class, 'store'])
    ->middleware('throttle:60,1')
    ->name('hotline-access.store');
Route::get('/informasi', [PublicPageController::class, 'informasiIndex'])->name('informasi.index');
Route::get('/informasi/dokumen/{document}/download', [PublicPageController::class, 'downloadDocument'])->name('informasi.documents.download');
Route::get('/informasi/{slug}', [PublicPageController::class, 'informasiShow'])->name('informasi.show');
Route::get('/galeri', [PublicPageController::class, 'galeri'])->name('galeri.index');

Route::redirect('/dashboard', '/admin')->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'admin', 'admin.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/audit-logs', [ActivityLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/aduan', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/aduan/create', [AdminComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/aduan', [AdminComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/aduan/export/excel', [AdminComplaintController::class, 'exportExcel'])->name('complaints.export.excel');
    Route::get('/aduan/export/pdf', [AdminComplaintController::class, 'exportPdf'])->name('complaints.export.pdf');
    Route::get('/aduan/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('/aduan/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.update-status');

    Route::get('/consultations/export/excel', [AdminConsultationController::class, 'exportExcel'])->name('consultations.export.excel');
    Route::get('/consultations/export/pdf', [AdminConsultationController::class, 'exportPdf'])->name('consultations.export.pdf');
    Route::resource('consultations', AdminConsultationController::class)->only(['index', 'update', 'show', 'edit']);

    Route::get('/location-monitoring/summary', [LocationMonitoringController::class, 'summary'])->name('location-monitoring.summary');
    Route::get('/location-monitoring', [LocationMonitoringController::class, 'index'])->name('location-monitoring.index');

    Route::resource('news-posts', NewsPostController::class)->except('show');
    Route::resource('leaders', LeaderController::class)->except('show');
    Route::resource('documents', DocumentController::class)->except('show');

    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('faqs', FaqController::class)->except('show');

    Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
