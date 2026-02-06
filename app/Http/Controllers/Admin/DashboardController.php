<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\GalleryItem;
use App\Models\Leader;
use App\Models\NewsPost;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'aduan_baru' => Complaint::query()->where('status', Complaint::STATUS_BARU)->count(),
                'aduan_diproses' => Complaint::query()->where('status', Complaint::STATUS_DIPROSES)->count(),
                'aduan_selesai' => Complaint::query()->where('status', Complaint::STATUS_SELESAI)->count(),
                'berita_event' => NewsPost::query()->count(),
                'dokumen' => Document::query()->count(),
                'galeri' => GalleryItem::query()->count(),
                'atasan' => Leader::query()->count(),
                'testimoni' => Testimonial::query()->count(),
            ],
            'recentComplaints' => Complaint::query()->latest()->take(10)->get(),
            'recentPosts' => NewsPost::query()->latest('published_at')->take(6)->get(),
        ]);
    }
}
