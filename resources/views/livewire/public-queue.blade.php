<div wire:poll.5s class="min-h-screen bg-slate-950 text-slate-100 pb-20">
    
    <!-- Header -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-black text-slate-950 text-lg shadow-lg shadow-cyan-500/20">
                    🎮
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-white leading-tight">
                        {{ $setting->streamer_name }}
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-block w-2 h-2 rounded-full {{ $setting->is_queue_open ? 'bg-emerald-400 animate-pulse' : 'bg-rose-500' }}"></span>
                        <span class="text-xs font-semibold {{ $setting->is_queue_open ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $setting->is_queue_open ? 'Antrean Mabar Buka' : 'Antrean Ditutup Sementara' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($setting->channel_url)
                <a 
                    href="{{ $setting->channel_url }}" 
                    target="_blank" 
                    class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition flex items-center gap-1.5"
                >
                    <span>Tonton Live</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            @endif
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 pt-6 space-y-6">

        <!-- Running Announcement -->
        @if($setting->announcement)
            <div class="p-3.5 rounded-2xl bg-gradient-to-r from-cyan-950/40 via-blue-950/30 to-slate-900 border border-cyan-500/30 text-xs text-cyan-200 flex items-center gap-2.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-cyan-400 shrink-0"></span>
                <span>{{ $setting->announcement }}</span>
            </div>
        @endif

        <!-- Card: Cek Posisi Antrean Saya (Viewer Self-Service) -->
        <div class="p-5 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-300 mb-2 flex items-center gap-2">
                🔍 Cek Nomor Giliran Anda
            </h2>
            <p class="text-xs text-slate-400 mb-3">
                Ketik In-Game ID atau Nama Saweria Anda untuk mengetahui nomor urut secara instan.
            </p>
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchId" 
                    placeholder="Ketik ID Game atau Nama Anda..."
                    class="w-full bg-slate-950 border border-slate-800 focus:border-cyan-500 rounded-2xl py-3 px-4 text-sm text-white focus:outline-none transition pl-11"
                >
                <svg class="w-5 h-5 text-slate-500 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            @if($userSearchResult)
                <div class="mt-4 p-4 rounded-2xl bg-cyan-950/60 border border-cyan-500/50 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500 text-slate-950 font-black text-xl flex items-center justify-center shrink-0 shadow-lg shadow-cyan-500/30">
                        #{{ $userSearchResult['position'] }}
                    </div>
                    <div>
                        <div class="text-xs text-cyan-300 font-bold uppercase">Posisi Antrean Anda</div>
                        <div class="text-base font-extrabold text-white">
                            {{ $userSearchResult['order']->customer_name }} ({{ $userSearchResult['order']->in_game_id }})
                        </div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            Sisa {{ $userSearchResult['order']->total_matches }} Match • Bersiap saat giliran Anda mendekati #1!
                        </div>
                    </div>
                </div>
            @elseif(!empty(trim($searchId)))
                <div class="mt-3 text-xs text-amber-400 italic">
                    ID atau nama tidak ditemukan di antrean aktif saat ini.
                </div>
            @endif
        </div>

        <!-- Section: Sedang Main (Now Playing) -->
        <div>
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-ping"></span>
                <h2 class="text-sm font-black uppercase tracking-wider text-white">
                    Sedang Main Bersama Streamer
                </h2>
            </div>

            @if($playingOrders->isEmpty())
                <div class="p-6 rounded-2xl bg-slate-900/50 border border-slate-800 text-center text-xs text-slate-500">
                    Lobby kosong. Streamer sedang bersiap atau menunggu pemain berikutnya.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($playingOrders as $active)
                        <div class="p-5 rounded-3xl bg-gradient-to-br from-slate-900 to-slate-950 border border-cyan-500/40 shadow-lg shadow-cyan-950/20">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                        {{ $active->game->name }}
                                    </span>
                                    <h3 class="text-lg font-black text-white mt-1">
                                        {{ $active->customer_name }}
                                    </h3>
                                    <div class="text-xs font-mono text-cyan-300">
                                        ID: {{ $active->in_game_id }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-slate-400 block font-bold">Sisa Game</span>
                                    <span class="text-xl font-black font-mono text-amber-400">
                                        {{ $active->remaining_matches }} <span class="text-xs text-slate-500">/ {{ $active->total_matches }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden mt-3">
                                <div class="h-full bg-gradient-to-r from-cyan-400 to-emerald-400 transition-all duration-300" style="width: {{ $active->progress_percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section: Daftar Antrean Lengkap -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-black uppercase tracking-wider text-white flex items-center gap-2">
                    <span>📋 Urutan Antrean Berikutnya</span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 font-medium">
                        {{ $queuedOrders->count() }} Menunggu
                    </span>
                </h2>
            </div>

            @if($queuedOrders->isEmpty())
                <div class="p-6 rounded-2xl bg-slate-900/50 border border-slate-800 text-center text-xs text-slate-500">
                    Tidak ada antrean menunggu saat ini. Mau mabar? Donasi sekarang lewat Saweria!
                </div>
            @else
                <div class="space-y-2.5">
                    @foreach($queuedOrders as $index => $item)
                        <div class="p-4 rounded-2xl transition flex items-center justify-between gap-3 {{ $item->is_fast_track ? 'bg-gradient-to-r from-amber-950/40 via-slate-900 to-slate-900 border-2 border-amber-500/50 shadow-md shadow-amber-950/30' : 'bg-slate-900/80 border border-slate-800' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl font-extrabold text-xs flex items-center justify-center shrink-0 border {{ $item->is_fast_track ? 'bg-amber-500 text-slate-950 border-amber-400 font-black' : 'bg-slate-800 text-amber-400 border-slate-700' }}">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($item->is_fast_track)
                                            <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                                ⚡ Fast Track VIP
                                            </span>
                                        @endif
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">
                                            {{ $item->game->name }}
                                        </span>
                                    </div>
                                    <div class="font-bold text-white text-sm">
                                        {{ $item->customer_name }}
                                    </div>
                                    <div class="text-[11px] font-mono text-slate-400">
                                        ID: {{ $item->in_game_id }}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-xs font-black {{ $item->is_fast_track ? 'text-amber-300' : 'text-slate-300' }} font-mono block">
                                    {{ $item->total_matches }} Match
                                </span>
                                <span class="text-[10px] text-slate-500">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section: Petunjuk Cara Order via Saweria -->
        <div class="p-5 rounded-3xl bg-slate-900/60 border border-slate-800">
            <h3 class="text-xs font-black uppercase tracking-wider text-amber-400 mb-2">
                💡 Cara Masuk Antrean via Saweria
            </h3>
            <p class="text-xs text-slate-300 mb-3">
                Donasi nominal sesuai jumlah match yang diinginkan dan gunakan format pesan berikut pada Saweria:
            </p>
            <div class="space-y-2 mb-3">
                <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs font-mono select-all">
                    <span class="text-slate-400 text-[11px] block font-sans">Antrean Reguler:</span>
                    <span class="text-cyan-300">MABAR [GAME] [ID_GAME] [CATATAN]</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950 border border-amber-500/30 text-xs font-mono select-all">
                    <span class="text-amber-400 text-[11px] block font-sans font-bold">⚡ Fast Track VIP (Prioritas Terdepan):</span>
                    <span class="text-amber-300">MABAR FAST [GAME] [ID_GAME] [CATATAN]</span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-950 border border-blue-500/30 text-xs font-mono select-all">
                    <span class="text-blue-400 text-[11px] block font-sans font-bold">+ Tambah Match (Pemain Aktif / Antrean):</span>
                    <span class="text-blue-300">TAMBAH [ID_GAME] atau EXTEND [NO_ORDER]</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                @foreach($games as $game)
                    <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                        <div class="font-bold text-white">{{ $game->name }}</div>
                        <div class="text-emerald-400 font-semibold font-mono">Rp {{ number_format($game->price_per_match, 0, ',', '.') }} / match</div>
                    </div>
                @endforeach
            </div>
        </div>

    </main>

</div>
