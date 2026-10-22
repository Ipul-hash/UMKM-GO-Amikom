@extends('layouts.admin')

@section('title', 'UMKMGo - Pengaturan Toko')

@section('content')
<main class="flex-1 px-8 py-7" data-purpose="main-layout" x-data="settingsManagement()" x-init="init()">
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium"
         :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-red-50 text-red-800 border-red-200'"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.show = false" class="text-slate-400 hover:text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </button>
    </div>

    <!-- Title & Subtitle Area -->
    <div class="mb-7">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Toko</h1>
        <p class="text-[13px] text-slate-500 mt-1">Kelola profil bisnis dan preferensi aplikasi</p>
    </div>

    <!-- Two-Column Settings Container Layout -->
    <div class="grid grid-cols-12 gap-6 items-start">
        <!-- BEGIN: SettingsSubNav -->
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-sm" data-purpose="settings-sub-navigation">
                <div class="px-2 pb-2.5">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">PENGATURAN TOKO</span>
                </div>
                <div class="space-y-1">
                    <!-- Tab 1: Profil Toko -->
                    <button @click="activeTab = 'profil'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-xs transition-colors"
                            :class="activeTab === 'profil' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Profil Toko</span>
                        </div>
                        <svg x-show="activeTab === 'profil'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <!-- Tab 2: Pajak & Biaya -->
                    <button @click="activeTab = 'pajak'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-xs transition-colors"
                            :class="activeTab === 'pajak' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" :class="activeTab === 'pajak' ? 'text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <line x1="19" x2="5" y1="5" y2="19"></line>
                                <circle cx="6.5" cy="6.5" r="2.5"></circle>
                                <circle cx="17.5" cy="17.5" r="2.5"></circle>
                            </svg>
                            <span>Pajak & Biaya</span>
                        </div>
                        <svg x-show="activeTab === 'pajak'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <!-- Tab 3: Struk Kasir -->
                    <button @click="activeTab = 'struk'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-xs transition-colors"
                            :class="activeTab === 'struk' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" :class="activeTab === 'struk' ? 'text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" x2="8" y1="13" y2="13"></line>
                                <line x1="16" x2="8" y1="17" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Struk Kasir</span>
                        </div>
                        <svg x-show="activeTab === 'struk'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>

                    <!-- Tab 4: Printer POS -->
                    <button @click="activeTab = 'printer'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-xs transition-colors"
                            :class="activeTab === 'printer' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 flex-shrink-0" :class="activeTab === 'printer' ? 'text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect height="8" width="12" x="6" y="14"></rect>
                            </svg>
                            <span>Printer POS</span>
                        </div>
                        <svg x-show="activeTab === 'printer'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- END: SettingsSubNav -->

        <!-- BEGIN: SettingsFormCard -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm flex flex-col" data-purpose="settings-form-panel">
                <div class="p-7">
                    <!-- ============================================== -->
                    <!-- TAB 1: PROFIL TOKO -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'profil'">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-slate-900">Profil Toko</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Perbarui identitas dan informasi kontak toko Anda.</p>
                        </div>

                        <!-- Store Logo Uploader Section -->
                        <div class="flex items-center gap-4 mb-7" data-purpose="store-logo-section">
                            <div class="w-20 h-20 rounded-xl border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center flex-shrink-0 text-slate-400 overflow-hidden relative shadow-sm">
                                <template x-if="(logoPreviewUrl || form.logo_url) && !logoError">
                                    <img :src="logoPreviewUrl || form.logo_url" 
                                         x-on:error="logoError = true"
                                         alt="Logo Toko" 
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!logoPreviewUrl && (!form.logo_url || logoError)">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <rect height="18" rx="2" ry="2" width="18" x="3" y="3"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                            <line x1="16" x2="16" y1="5" y2="9"></line>
                                            <line x1="14" x2="18" y1="7" y2="7"></line>
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <input type="file" x-ref="logoInput" @change="uploadLogo($event)" class="hidden" accept="image/png,image/jpeg,image/webp">
                                <div class="flex items-center gap-2">
                                    <button @click="$refs.logoInput.click()" class="inline-flex items-center justify-center gap-2 px-3.5 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm w-fit" type="button">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" x2="12" y1="3" y2="15"></line>
                                        </svg>
                                        <span x-text="uploadingLogo ? 'Mengunggah...' : 'Upload Logo Toko'"></span>
                                    </button>
                                    <template x-if="(form.logo_url || logoPreviewUrl) && !logoError">
                                        <button @click="deleteLogo()" class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1">
                                            Hapus Logo
                                        </button>
                                    </template>
                                </div>
                                <span class="text-[11px] text-slate-400">JPG, PNG atau WebP. Maksimal 2 MB.</span>
                            </div>
                        </div>

                        <!-- Form Fields Grid -->
                        <div class="space-y-4">
                            <!-- Grid Row 1: Nama Toko & Nama Pemilik -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Toko *</label>
                                    <input x-model="form.name" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" placeholder="Nama Toko"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Pemilik</label>
                                    <input x-model="form.owner_name" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" placeholder="Nama Pemilik Toko"/>
                                </div>
                            </div>

                            <!-- Grid Row 2: No. WhatsApp & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">No. WhatsApp</label>
                                    <input x-model="form.phone" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" placeholder="0812-xxxx-xxxx"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Email Toko</label>
                                    <input x-model="form.email" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="email" placeholder="admin@umkmgo.id"/>
                                </div>
                            </div>

                            <!-- Grid Row 3: Jenis Usaha & Kota -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Kategori / Jenis Usaha</label>
                                    <input x-model="form.business_type" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" placeholder="Contoh: Kafe & Retail Fashion"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Kota / Kabupaten</label>
                                    <input x-model="form.city" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" placeholder="Contoh: Banyumas / Purwokerto"/>
                                </div>
                            </div>

                            <!-- Grid Row 4: Alamat -->
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Alamat Lengkap</label>
                                <textarea x-model="form.address_line" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none" rows="3" placeholder="Alamat outlet atau toko fisik"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: PAJAK & BIAYA -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'pajak'" style="display: none;">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-slate-900">Pajak & Biaya Tambahan</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Atur penerapan pajak restoran/PPN dan biaya layanan pada kasir dan pesanan.</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Toggle Pajak -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-bold text-slate-900">Aktifkan Pajak (PB1 / PPN)</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Secara otomatis menghitung pajak pada struk kasir dan pesanan.</div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="form.settings.tax.enabled" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <!-- Tarif Pajak -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Tarif Pajak (%)</label>
                                    <input type="number" x-model="form.settings.tax.rate" min="0" max="100" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Contoh: 10 atau 11"/>
                                    <p class="text-[11px] text-slate-400 mt-1">Standar PB1 daerah atau PPN nasional.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Biaya Layanan / Service Fee (%)</label>
                                    <input type="number" x-model="form.settings.tax.service_fee_rate" min="0" max="100" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="0 jika tidak ada"/>
                                    <p class="text-[11px] text-slate-400 mt-1">Dapat dikenakan untuk dine-in atau operasional meja.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: STRUK KASIR -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'struk'" style="display: none;">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-slate-900">Format Struk Kasir</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Sesuaikan teks header, footer, dan informasi tambahan pada struk belanja.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Form Struk -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Header Struk</label>
                                    <input type="text" x-model="form.settings.receipt.header" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Contoh: Terima kasih atas kunjungan Anda!"/>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Footer / Pesan Terima Kasih</label>
                                    <textarea x-model="form.settings.receipt.footer" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none" rows="2" placeholder="Barang yang dibeli tidak dapat ditukar"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Info WiFi & Media Sosial</label>
                                    <input type="text" x-model="form.settings.receipt.wifi_info" class="w-full text-xs text-slate-800 rounded-lg border-slate-200 py-2.5 px-3 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="WiFi: KopiAndi / IG: @kopiandi"/>
                                </div>

                                <div class="space-y-2 pt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="form.settings.receipt.show_logo" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-medium text-slate-700">Tampilkan Logo Toko di Bagian Atas Struk</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="form.settings.receipt.show_queue_number" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-medium text-slate-700">Cetak Nomor Antrean Pesanan Besar</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Live Preview Struk -->
                            <div>
                                <div class="text-xs font-semibold text-slate-600 mb-2">Preview Struk Kasir:</div>
                                <div class="bg-amber-50/40 border border-amber-200/60 rounded-xl p-4 font-mono text-[11px] text-slate-800 shadow-sm max-w-[280px] mx-auto">
                                    <div class="text-center font-bold text-xs" x-text="form.name || 'UMKMGo Store'"></div>
                                    <div class="text-center text-[10px] text-slate-500" x-text="form.address_line || 'Alamat Toko'"></div>
                                    <div class="text-center text-[10px] text-slate-500 mb-2" x-text="form.phone || '0812-xxxx-xxxx'"></div>
                                    <div class="border-t border-dashed border-slate-300 my-2"></div>
                                    <div class="text-center text-[10px] italic mb-1" x-text="form.settings.receipt.header"></div>
                                    <div class="flex justify-between">
                                        <span>1x Americano</span>
                                        <span>15.000</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>2x Matcha Latte</span>
                                        <span>30.000</span>
                                    </div>
                                    <div class="border-t border-dashed border-slate-300 my-2"></div>
                                    <div class="flex justify-between font-bold">
                                        <span>TOTAL</span>
                                        <span>Rp 45.000</span>
                                    </div>
                                    <div class="border-t border-dashed border-slate-300 my-2"></div>
                                    <div class="text-center text-[9px] text-slate-500" x-text="form.settings.receipt.footer"></div>
                                    <div class="text-center text-[9px] font-semibold text-blue-600 mt-1" x-text="form.settings.receipt.wifi_info"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 4: PRINTER POS -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'printer'" style="display: none;">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-slate-900">Pengaturan Printer Thermal POS</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Konfigurasi cetak struk otomatis dan ukuran kertas thermal.</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Lebar Kertas -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-2">Ukuran Lebar Kertas Thermal</label>
                                <div class="grid grid-cols-2 gap-4 max-w-md">
                                    <label class="border rounded-xl p-3 flex items-center gap-3 cursor-pointer transition"
                                           :class="form.settings.printer.paper_width === '58mm' ? 'border-blue-600 bg-blue-50/50 text-blue-900' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="radio" value="58mm" x-model="form.settings.printer.paper_width" class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <div class="text-xs font-bold">58 mm</div>
                                            <div class="text-[10px] text-slate-500">Printer mini portabel / kasir kecil</div>
                                        </div>
                                    </label>

                                    <label class="border rounded-xl p-3 flex items-center gap-3 cursor-pointer transition"
                                           :class="form.settings.printer.paper_width === '80mm' ? 'border-blue-600 bg-blue-50/50 text-blue-900' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="radio" value="80mm" x-model="form.settings.printer.paper_width" class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <div class="text-xs font-bold">80 mm</div>
                                            <div class="text-[10px] text-slate-500">Printer kasir desktop standar</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Opsi Cetak Otomatis -->
                            <div class="space-y-3 pt-3 border-t border-slate-100 max-w-md">
                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" x-model="form.settings.printer.auto_print" class="rounded text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="text-xs font-semibold text-slate-800">Cetak Struk Otomatis</div>
                                        <div class="text-[11px] text-slate-400">Langsung memicu cetak saat transaksi kasir berhasil diselesaikan</div>
                                    </div>
                                </label>

                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" x-model="form.settings.printer.kitchen_copy" class="rounded text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <div class="text-xs font-semibold text-slate-800">Cetak Salinan Dapur (Kitchen Ticket)</div>
                                        <div class="text-[11px] text-slate-400">Mencetak tiket pesanan tambahan untuk barista atau koki dapur</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Action Footer -->
                <div class="px-7 py-4 border-t border-slate-100 flex justify-end items-center">
                    <button @click="saveSettings()" :disabled="loading" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-5 py-2.5 rounded-lg shadow-sm transition disabled:opacity-50" type="button">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        <span x-text="loading ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- END: SettingsFormCard -->
    </div>
</main>

<script>
function settingsManagement() {
    return {
        activeTab: 'profil',
        loading: false,
        uploadingLogo: false,
        logoPreviewUrl: '',
        logoError: false,
        form: {
            id: null,
            name: '',
            owner_name: '',
            phone: '',
            email: '',
            business_type: '',
            address_line: '',
            city: '',
            description: '',
            logo_url: '',
            settings: {
                tax: {
                    enabled: false,
                    rate: 10,
                    service_fee_rate: 0
                },
                receipt: {
                    header: 'Terima kasih atas kunjungan Anda!',
                    footer: 'Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.',
                    show_logo: true,
                    show_queue_number: true,
                    wifi_info: 'WiFi: UMKMGo_Free / pass: amikom123'
                },
                printer: {
                    paper_width: '58mm',
                    auto_print: true,
                    kitchen_copy: false
                }
            }
        },
        toast: {
            show: false,
            message: '',
            type: 'success'
        },
        showNotification(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3500);
        },
        async init() {
            await this.fetchSettings();
        },
        async fetchSettings() {
            this.loading = true;
            try {
                const res = await fetch('/api/v1/operasional/pengaturan');
                const json = await res.json();
                if (json.success) {
                    this.form = json.data;
                    this.logoError = false;
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Gagal memuat pengaturan toko', 'error');
            } finally {
                this.loading = false;
            }
        },
        async saveSettings() {
            this.loading = true;
            try {
                const res = await fetch('/api/v1/operasional/pengaturan', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.showNotification(json.message);
                    this.form = json.data;
                    this.logoError = false;
                } else {
                    this.showNotification(json.message || 'Gagal menyimpan pengaturan', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Terjadi kesalahan sistem', 'error');
            } finally {
                this.loading = false;
            }
        },
        async uploadLogo(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                this.logoPreviewUrl = event.target.result;
                this.logoError = false;
            };
            reader.readAsDataURL(file);

            const formData = new FormData();
            formData.append('logo', file);

            this.uploadingLogo = true;
            try {
                const res = await fetch('/api/v1/operasional/pengaturan/upload-logo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const json = await res.json();
                if (res.ok && json.success) {
                    this.form.logo_url = json.logo_url;
                    this.logoPreviewUrl = json.logo_url;
                    this.logoError = false;
                    this.showNotification('Logo toko berhasil diperbarui');
                } else {
                    this.showNotification(json.message || 'Gagal mengunggah logo', 'error');
                }
            } catch (err) {
                console.error(err);
                this.showNotification('Gagal mengunggah file', 'error');
            } finally {
                this.uploadingLogo = false;
            }
        },
        async deleteLogo() {
            this.form.logo_url = '';
            this.logoPreviewUrl = '';
            this.logoError = false;
            await this.saveSettings();
            this.showNotification('Logo toko berhasil dihapus');
        }
    };
}
</script>
@endsection
