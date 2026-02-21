<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LeaderController;
use App\Http\Controllers\Admin\LocationMonitoringController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HotlineAccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SubAgentController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

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
Route::get('/informasi/dokumen/{document}/preview', [PublicPageController::class, 'previewDocument'])->name('informasi.documents.preview');
Route::get('/informasi/{slug}', [PublicPageController::class, 'informasiShow'])->name('informasi.show');
Route::get('/galeri', [PublicPageController::class, 'galeri'])->name('galeri.index');

use App\Http\Controllers\TestimonialSubmissionController;
Route::get('/testimoni/isi/{token}', [TestimonialSubmissionController::class, 'formByToken'])->name('testimonials.form');
Route::post('/testimoni/isi/{token}', [TestimonialSubmissionController::class, 'storeByToken'])->name('testimonials.store-token');
Route::get('/testimoni/terima-kasih/{token}', [TestimonialSubmissionController::class, 'thankyou'])->name('testimonials.thankyou');

Route::redirect('/dashboard', '/admin')->middleware('auth')->name('dashboard');

// -------------------------------------------------------
// Route Admin — semua admin (super_admin & admin) bisa akses
// -------------------------------------------------------
Route::middleware(['auth', 'admin', 'admin.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/audit-logs', [ActivityLogController::class, 'index'])->name('audit-logs.index');

    // Operasional: Pengaduan
    Route::get('/aduan', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/aduan/create', [AdminComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/aduan', [AdminComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/aduan/export/excel', [AdminComplaintController::class, 'exportExcel'])->name('complaints.export.excel');
    Route::get('/aduan/export/pdf', [AdminComplaintController::class, 'exportPdf'])->name('complaints.export.pdf');
    Route::get('/aduan/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('/aduan/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::patch('/aduan/{complaint}/generate-token', [AdminComplaintController::class, 'generateTestimonialToken'])->name('complaints.generate-token');

    // Operasional: Konsultasi
    Route::get('/consultations/export/excel', [AdminConsultationController::class, 'exportExcel'])->name('consultations.export.excel');
    Route::get('/consultations/export/pdf', [AdminConsultationController::class, 'exportPdf'])->name('consultations.export.pdf');
    Route::patch('/consultations/{consultation}/generate-token', [AdminConsultationController::class, 'generateTestimonialToken'])->name('consultations.generate-token');
    Route::resource('consultations', AdminConsultationController::class)->only(['index', 'update', 'show', 'edit']);

    // Operasional: Pantau Lokasi
    Route::get('/location-monitoring/summary', [LocationMonitoringController::class, 'summary'])->name('location-monitoring.summary');
    Route::get('/location-monitoring', [LocationMonitoringController::class, 'index'])->name('location-monitoring.index');

    // -------------------------------------------------------
    // Konten Publik & Sistem
    // -------------------------------------------------------
    Route::post('news-posts/import-instagram', [NewsPostController::class, 'importInstagram'])->name('news-posts.import-instagram');
    Route::resource('news-posts', NewsPostController::class)->except('show');
    Route::resource('leaders', LeaderController::class)->except('show');
    Route::resource('documents', DocumentController::class)->except('show');

    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::post('/testimonials/auto-approve', [TestimonialController::class, 'autoApprove'])->name('testimonials.auto-approve');
    Route::patch('/testimonials/{testimonial}/toggle-publish', [TestimonialController::class, 'togglePublish'])->name('testimonials.toggle-publish');
    Route::resource('faqs', FaqController::class)->except('show');

    Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // Manajemen Pengguna (HANYA Super Admin)
    Route::middleware('super_admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

