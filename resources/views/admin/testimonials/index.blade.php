@extends('layouts.admin')

@section('title', 'Admin Testimoni')
@section('page_title', 'Testimoni')

@section('content')

    {{-- ===== DASHBOARD KEPUASAN ===== --}}
    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="kpi-card rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 p-5 text-white shadow-lg">
            <div class="mb-1 text-xs font-medium uppercase tracking-wider opacity-80">Rata-rata Rating</div>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-extrabold leading-none">{{ $averageRating }}</span>
                <span class="mb-1 text-lg opacity-90">/5</span>
            </div>
            <div class="mt-2 flex gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 {{ $i <= round($averageRating) ? 'text-white' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
        </div>

        <div class="kpi-card rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-1 text-xs font-medium uppercase tracking-wider text-slate-400">Total Testimoni</div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $totalCount }}</div>
            <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $verifiedCount }} terverifikasi
            </div>
        </div>

        <div class="kpi-card rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-1 text-xs font-medium uppercase tracking-wider text-slate-400">Disetujui Tampil</div>
            <div class="text-3xl font-extrabold text-emerald-600">{{ $publishedCount }}</div>
            <div class="mt-2">
                @if($totalCount > 0)
                <div class="h-1.5 w-full rounded-full bg-slate-100">
                    <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ round(($publishedCount / $totalCount) * 100) }}%"></div>
                </div>
                @endif
            </div>
        </div>

        <div class="kpi-card rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="mb-1 text-xs font-medium uppercase tracking-wider text-slate-400">Menunggu Persetujuan</div>
            <div class="text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</div>
            @if($pendingCount > 0)
            <div class="mt-2 text-xs text-amber-600 font-medium">⚠ Perlu ditinjau</div>
            @else
            <div class="mt-2 text-xs text-slate-400">Semua sudah ditinjau</div>
            @endif
        </div>
    </section>

    {{-- ===== DISTRIBUSI BINTANG ===== --}}
    <section class="dist-section mb-6 rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
        <h3 class="mb-3 text-sm font-semibold text-slate-700">Distribusi Rating</h3>
        <div class="space-y-2">
            @foreach ($starDistribution as $star => $data)
            <div class="flex items-center gap-3">
                <span class="w-8 text-right text-sm font-semibold text-slate-600">{{ $star }}★</span>
                <div class="flex-1 h-3 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $star >= 4 ? 'bg-emerald-400' : ($star >= 3 ? 'bg-amber-400' : 'bg-red-400') }}"
                         style="width: {{ $data['percentage'] }}%"></div>
                </div>
                <span class="w-16 text-right text-xs text-slate-500">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ===== TOOLBAR ===== --}}
    <section class="mb-4 rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-xs font-medium text-slate-500" style="cursor:default;user-select:none">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, isi, atau relasi..."
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 placeholder-slate-400 focus:border-navy-500 focus:outline-none focus:ring-1 focus:ring-navy-500">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" style="cursor:default;user-select:none">Status</label>
                <select name="filter" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-navy-500 focus:outline-none focus:ring-1 focus:ring-navy-500" style="cursor:pointer">
                    <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="disetujui" {{ $filter == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="menunggu" {{ $filter == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="terverifikasi" {{ $filter == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500" style="cursor:default;user-select:none">Urutkan</label>
                <select name="sort" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-navy-500 focus:outline-none focus:ring-1 focus:ring-navy-500" style="cursor:pointer">
                    <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="rating_tinggi" {{ $sort == 'rating_tinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="rating_rendah" {{ $sort == 'rating_rendah' ? 'selected' : '' }}>Rating Terendah</option>
                </select>
            </div>
            <button type="submit" class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800" style="cursor:pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Terapkan
            </button>
            @if(request('search') || request('filter', 'semua') !== 'semua' || request('sort', 'terbaru') !== 'terbaru')
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-sm btn-ghost text-slate-500">Reset</a>
            @endif
        </form>
    </section>

    {{-- ===== ACTION BAR ===== --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            @if($pendingCount > 0)
            <form method="POST" action="{{ route('admin.testimonials.auto-approve') }}" onsubmit="return confirm('Setujui otomatis semua testimoni dengan rating ≥ 3 bintang?')">
                @csrf
                <input type="hidden" name="min_rating" value="3">
                <button type="submit" class="btn btn-sm border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100" style="cursor:pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Auto Setujui (★ ≥ 3)
                </button>
            </form>
            <form method="POST" action="{{ route('admin.testimonials.auto-approve') }}" onsubmit="return confirm('Setujui SEMUA testimoni yang menunggu, termasuk rating rendah?')">
                @csrf
                <input type="hidden" name="min_rating" value="1">
                <button type="submit" class="btn btn-sm border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100" style="cursor:pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui Semua
                </button>
            </form>
            @endif
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Testimoni
        </a>
    </div>

    {{-- ===== TABEL + MODAL ===== --}}
    <section class="tbl-section rounded-2xl bg-white shadow-sm border border-slate-100" x-data="{ showDetail: false, detail: {} }">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pengirim</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500">Isi Testimoni</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Rating</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Status</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Sumber</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Tanggal</th>
                        <th class="text-xs font-semibold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                    <tr data-clickable class="border-b border-slate-50 hover:bg-blue-50/40 transition-colors"
                        @click="if (!$event.target.closest('form, a, button')) {
                            detail = @js([
                                'name' => $testimonial->name,
                                'relation' => $testimonial->relation ?: 'Masyarakat',
                                'phone' => $testimonial->phone_number ?: '-',
                                'content' => $testimonial->content,
                                'rating' => $testimonial->rating,
                                'is_published' => $testimonial->is_published,
                                'is_verified' => (bool)$testimonial->is_verified,
                                'date' => $testimonial->created_at->format('d M Y, H:i'),
                                'source_type' => $testimonial->consultation_id ? 'Konsultasi' : ($testimonial->complaint_id ? 'Aduan' : 'Langsung'),
                                'edit_url' => route('admin.testimonials.edit', $testimonial),
                            ]);
                            showDetail = true;
                        }">
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800">{{ $testimonial->name }}</div>
                            <div class="text-xs text-slate-400">{{ $testimonial->relation ?: 'Masyarakat' }}</div>
                        </td>
                        <td class="max-w-xs py-3 px-4">
                            <p class="truncate text-sm text-slate-600">{{ Str::limit($testimonial->content, 80) }}</p>
                        </td>
                        <td class="text-center py-3 px-4">
                            <div class="inline-flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-3.5 w-3.5 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($testimonial->is_published)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Tampil
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Menunggu
                                </span>
                            @endif
                        </td>
                        <td class="text-center py-3 px-4">
                            @if($testimonial->is_verified)
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="text-xs text-slate-400">Manual</span>
                            @endif
                        </td>
                        <td class="text-center text-xs text-slate-500 py-3 px-4">{{ $testimonial->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4" style="cursor:default">
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="{{ route('admin.testimonials.toggle-publish', $testimonial) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="rounded-lg p-1.5 hover:bg-slate-100 transition-colors" style="cursor:pointer"
                                            title="{{ $testimonial->is_published ? 'Batalkan tampil' : 'Setujui tampil' }}">
                                        @if($testimonial->is_published)
                                            <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                        @else
                                            <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="rounded-lg p-1.5 hover:bg-slate-100 transition-colors" style="cursor:pointer" title="Edit">
                                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni dari {{ $testimonial->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg p-1.5 hover:bg-red-50 transition-colors" style="cursor:pointer" title="Hapus">
                                        <svg class="h-4 w-4 text-red-400 hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="mt-2 text-sm text-slate-500">Belum ada testimoni yang sesuai filter.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($testimonials->hasPages())
        <div class="border-t border-slate-100 px-5 py-3">{{ $testimonials->links() }}</div>
        @endif

        {{-- MODAL DETAIL --}}
        <div x-show="showDetail" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
             @click.self="showDetail = false" @keydown.escape.window="showDetail = false">

            <div x-show="showDetail"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl" @click.stop style="cursor:default">

                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-bold text-slate-800">Detail Testimoni</h3>
                    <button @click="showDetail = false" class="rounded-lg p-1 hover:bg-slate-100 transition-colors" style="cursor:pointer">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-lg font-bold text-slate-800" x-text="detail.name"></div>
                            <div class="text-sm text-slate-500" x-text="detail.relation"></div>
                        </div>
                        <div class="flex-shrink-0 flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5">
                            <template x-for="i in 5">
                                <svg class="h-4 w-4" :class="i <= detail.rating ? 'text-amber-400' : 'text-slate-200'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </template>
                            <span class="ml-1 text-sm font-bold text-amber-600" x-text="detail.rating + '/5'"></span>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 text-xs font-medium uppercase tracking-wider text-slate-400">Isi Testimoni</div>
                        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700" style="user-select:text;cursor:text" x-text="detail.content"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-400 mb-0.5">Status</div>
                            <template x-if="detail.is_published">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Tampil
                                </span>
                            </template>
                            <template x-if="detail.is_published === false">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span> Menunggu
                                </span>
                            </template>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-400 mb-0.5">Sumber</div>
                            <template x-if="detail.is_verified">
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Terverifikasi
                                </span>
                            </template>
                            <template x-if="detail.is_verified === false">
                                <span class="text-sm text-slate-500">Manual</span>
                            </template>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-400 mb-0.5">Asal Layanan</div>
                            <div class="text-sm font-semibold text-slate-700" x-text="detail.source_type"></div>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-400 mb-0.5">Tanggal</div>
                            <div class="text-sm font-semibold text-slate-700" x-text="detail.date"></div>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3 col-span-2">
                            <div class="text-xs font-medium text-slate-400 mb-0.5">No. HP</div>
                            <div class="text-sm font-semibold text-slate-700" x-text="detail.phone"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 px-6 py-3">
                    <button @click="showDetail = false" class="btn btn-sm btn-ghost text-slate-500" style="cursor:pointer">Tutup</button>
                    <a :href="detail.edit_url" class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800" style="cursor:pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Testimoni
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
