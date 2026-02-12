@extends('layouts.admin')

@section('title', 'Detail Konsultasi')
@section('page_title', 'Detail Konsultasi')

@push('head')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush

@section('content')
    <div x-data="consultationDetail" class="grid gap-6 lg:grid-cols-3 relative">
        <!-- Toast Notification -->
        <div
            x-show="showToast"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed top-20 right-5 z-50 flex items-center gap-2 rounded-lg bg-green-500 px-4 py-3 text-white shadow-lg"
            style="display: none;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span x-text="toastMessage" class="font-medium"></span>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-heading text-lg font-bold text-navy-700">Permasalahan Klien</h3>
                    <span class="text-sm text-slate-500">{{ $consultation->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="prose prose-slate max-w-none">
                    <p class="whitespace-pre-wrap">{{ $consultation->permasalahan }}</p>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-heading text-lg font-bold text-navy-700">Rekomendasi / Tanggapan</h3>
                    <template x-if="rekomendasi">
                        <span class="badge bg-green-100 text-green-700 border-0">Sudah Ditanggapi</span>
                    </template>
                    <template x-if="!rekomendasi">
                        <span class="badge bg-red-100 text-red-600 border-0">Belum Ditanggapi</span>
                    </template>
                </div>

                <!-- View Mode -->
                <div x-show="!isEditing && rekomendasi">
                    <div class="rounded-xl bg-green-50 p-5 border border-green-100">
                        <p class="whitespace-pre-wrap text-slate-700" x-text="rekomendasi"></p>
                        <p class="mt-4 text-xs text-green-600 font-medium">Terakhir diperbarui: <span x-text="updatedAt"></span></p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button @click="startEditing" class="btn btn-outline border-slate-300 text-slate-700 hover:bg-slate-50">
                            Edit Rekomendasi
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div x-show="!isEditing && !rekomendasi" class="rounded-xl border border-dashed border-slate-300 p-8 text-center">
                    <p class="text-slate-500 mb-4">Belum ada rekomendasi yang diberikan untuk konsultasi ini.</p>
                    <button @click="startEditing" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0">
                        Isi Rekomendasi Sekarang
                    </button>
                </div>

                <!-- Edit Form -->
                <div x-show="isEditing" style="display: none;">
                    <form @submit.prevent="saveRecommendation">
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold text-slate-700">Isi Rekomendasi Anda</span>
                            </label>
                            <textarea 
                                x-model="formRekomendasi" 
                                class="textarea textarea-bordered h-32 w-full focus:border-navy-600 focus:outline-none" 
                                placeholder="Tuliskan solusi atau langkah selanjutnya..."></textarea>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <button type="button" @click="cancelEditing" class="btn btn-ghost text-slate-500 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0" :disabled="isSubmitting">
                                <span x-show="isSubmitting" class="loading loading-spinner loading-xs mr-2"></span>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="font-heading text-lg font-bold text-navy-700 mb-4">Info Klien</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Nama Lengkap</p>
                        <p class="text-base font-medium text-slate-800">{{ $consultation->nama_klien }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">ID Konsultasi</p>
                        <p class="text-base font-mono text-slate-600">#{{ $consultation->id }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Tanggal Masuk</p>
                        <p class="text-sm text-slate-600">{{ $consultation->created_at->translatedFormat('l, d F Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $consultation->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 space-y-2">
                    @if($consultation->latitude && $consultation->longitude)
                        <a href="{{ route('admin.location-monitoring.index', ['lat' => $consultation->latitude, 'lng' => $consultation->longitude]) }}" 
                           class="btn w-full justify-start bg-indigo-500 text-white hover:bg-indigo-600 border-0 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lihat Lokasi di Peta
                        </a>
                    @endif
                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-ghost w-full justify-start text-slate-600 hover:text-navy-700 hover:bg-slate-50 px-0">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('consultationDetail', () => ({
                isEditing: false,
                isSubmitting: false,
                showToast: false,
                toastMessage: '',
                rekomendasi: @json($consultation->rekomendasi),
                formRekomendasi: @json($consultation->rekomendasi),
                updatedAt: '{{ $consultation->updated_at->translatedFormat('d F Y, H:i') }}',

                startEditing() {
                    this.formRekomendasi = this.rekomendasi;
                    this.isEditing = true;
                },

                cancelEditing() {
                    this.isEditing = false;
                    this.formRekomendasi = this.rekomendasi;
                },

                async saveRecommendation() {
                    if (!this.formRekomendasi) {
                        alert('Rekomendasi tidak boleh kosong');
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('rekomendasi', this.formRekomendasi);

                        const response = await fetch(`{{ route('admin.consultations.update', $consultation->id) }}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        if (response.ok) {
                            this.rekomendasi = this.formRekomendasi;
                            this.isEditing = false;
                            
                            // Update timestamp to now
                            const now = new Date();
                            const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                            // Note: locally formatted date might slightly differ from server locale but acceptable for UX responsiveness
                            // Or we could parse the response if we changed controller to return JSON.
                            // Currently sticking to simple local update.
                            this.updatedAt = 'Baru saja'; 

                            this.showToastNotification('Rekomendasi berhasil disimpan');
                        } else {
                            alert('Gagal menyimpan. Silakan coba lagi.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan jaringan.');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                showToastNotification(message) {
                    this.toastMessage = message;
                    this.showToast = true;
                    setTimeout(() => {
                        this.showToast = false;
                    }, 3000);
                }
            }));
        });
    </script>
@endsection
