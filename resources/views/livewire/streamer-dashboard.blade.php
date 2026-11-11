<div wire:poll.4s class="min-h-screen bg-slate-950 text-slate-100 pb-20">
    
    <!-- Top Navigation & Control Bar -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between gap-4">
            
            <!-- Logo & Streamer Name -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 text-slate-950 font-extrabold text-xl">
                    ⚡
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-cyan-400 to-blue-300 bg-clip-text text-transparent">
                            MabarTrack
                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-800 text-slate-300 border border-slate-700 font-medium">v1.0</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-full {{ $is_queue_open ? 'bg-emerald-400 animate-ping' : 'bg-rose-400' }}"></span>
                        {{ $setting->streamer_name }} 
                        <span class="text-slate-500">•</span>
                        <button wire:click="toggleQueueStatus" class="hover:underline font-semibold {{ $is_queue_open ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $is_queue_open ? 'ANTREAN DIBUKA' : 'ANTREAN DITUTUP' }}
                        </button>
                    </p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2 sm:gap-3 flex-wrap justify-end">
                <!-- Simulasi Saweria Button -->
                <button 
                    wire:click="$set('showSimulateSaweriaModal', true)" 
                    class="px-3.5 py-2 rounded-xl bg-amber-500/15 hover:bg-amber-500/25 border border-amber-500/40 text-amber-300 text-xs sm:text-sm font-semibold flex items-center gap-2 transition active:scale-95"
                    title="Uji coba donasi Saweria tanpa uang sungguhan"
                >
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Simulasi Saweria</span>
                </button>

                <!-- Tambah Manual Button -->
                <button 
                    wire:click="$set('showAddModal', true)" 
                    class="px-3.5 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-slate-950 font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg shadow-cyan-500/20 transition active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Antrean</span>
                </button>

                <!-- OBS Overlay Link -->
                <a 
                    href="{{ url('/overlay/' . $setting->overlay_token) }}" 
                    target="_blank" 
                    class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs sm:text-sm font-medium flex items-center gap-1.5 transition"
                    title="Buka tampilan transparan untuk Browser Source OBS"
                >
                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden md:inline">Layar OBS</span>
                </a>

                <!-- Public Tracker Link -->
                <a 
                    href="{{ url('/queue') }}" 
                    target="_blank" 
                    class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs sm:text-sm font-medium flex items-center gap-1.5 transition"
                    title="Link publik untuk dibagikan ke penonton di live chat"
                >
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span class="hidden md:inline">Link Penonton</span>
                </a>

                <!-- Settings Icon Button -->
                <button 
                    wire:click="$set('showSettingsModal', true)"
                    class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 transition"
                    title="Pengaturan Streamer"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

        </div>
    </header>

    <!-- Main Workspace Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

        <!-- Stats Counter Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-sm shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sedang Mabar</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black text-cyan-400">{{ $playingOrders->count() }}</span>
                    <span class="text-xs text-slate-500 font-medium">Player</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-sm shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Antrean Menunggu</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black text-amber-400">{{ $queuedOrders->count() }}</span>
                    <span class="text-xs text-slate-500 font-medium">Player</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-sm shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Match Selesai Sesi Ini</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-black text-emerald-400">{{ $totalMatchesPlayed }}</span>
                    <span class="text-xs text-slate-500 font-medium">Game</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800/80 backdrop-blur-sm shadow-sm">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Donasi Sesi</span>
                <div class="mt-1 flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-black text-slate-200">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-6 p-3 rounded-2xl bg-slate-900/60 border border-slate-800">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                <button 
                    wire:click="$set('selectedGameId', 'all')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $selectedGameId === 'all' ? 'bg-cyan-500 text-slate-950 shadow-md shadow-cyan-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}"
                >
                    Semua Game
                </button>
                @foreach ($games as $game)
                    <button 
                        wire:click="$set('selectedGameId', '{{ $game->id }}')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $selectedGameId == $game->id ? 'bg-cyan-500 text-slate-950 shadow-md shadow-cyan-500/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}"
                    >
                        {{ $game->name }}
                    </button>
                @endforeach
            </div>

            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Cari nama / ID player..."
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-1.5 text-xs text-slate-200 focus:outline-none focus:border-cyan-500 transition pl-8"
                >
                <svg class="w-4 h-4 text-slate-500 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- UNIFIED QUEUE ROSTER (SATU DRAFT ALUR KE BAWAH) -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-cyan-400 animate-ping"></span>
                    <h2 class="text-lg font-black tracking-tight text-white uppercase flex items-center gap-2">
                        🎮 Alur Antrean Mabar Streamer
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-xs font-bold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                        {{ $playingOrders->count() }} Sedang Main
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-bold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        {{ $queuedOrders->count() }} Menunggu
                    </span>
                </div>
            </div>

            @if ($playingOrders->isEmpty() && $queuedOrders->isEmpty())
                <div class="p-8 rounded-2xl border border-dashed border-slate-800 bg-slate-900/30 text-center">
                    <p class="text-slate-400 font-medium text-sm">Belum ada antrean maupun pemain aktif saat ini.</p>
                    <p class="text-slate-500 text-xs mt-1">Donasi Saweria yang masuk atau input antrean manual baru akan otomatis muncul di sini.</p>
                </div>
            @else
                <div class="space-y-2.5">
                    
                    <!-- 1. ACTIVE PLAYERS (SEDANG MAIN - POSISI TERATAS) -->
                    @foreach ($playingOrders as $order)
                        <div class="relative rounded-2xl bg-gradient-to-r from-cyan-950/40 via-slate-900 to-slate-900/95 border-2 border-cyan-500/50 shadow-lg shadow-cyan-950/40 p-4 transition">
                            
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                
                                <!-- Left: Badge, Player Name & In-Game ID -->
                                <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                                    <div class="shrink-0 flex flex-col items-center gap-1">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 flex items-center gap-1.5 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-ping"></span>
                                            SEDANG MAIN
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 font-mono">
                                            {{ $order->order_number }}
                                        </span>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-cyan-300 border border-slate-700">
                                                {{ $order->game->name }}
                                            </span>
                                            <h3 class="text-base font-black text-white truncate">
                                                {{ $order->customer_name }}
                                                @if($order->in_game_nickname)
                                                    <span class="text-xs font-medium text-slate-400">({{ $order->in_game_nickname }})</span>
                                                @endif
                                            </h3>
                                        </div>

                                        <!-- In-game ID & Quick copy -->
                                        <div x-data="{ copied: false }" class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-mono font-bold text-cyan-300 bg-slate-950/80 px-2 py-0.5 rounded border border-slate-800 select-all">
                                                ID: {{ $order->in_game_id }}
                                            </span>
                                            <button 
                                                @click="navigator.clipboard.writeText('{{ $order->in_game_id }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="text-[11px] font-bold transition px-2 py-0.5 rounded"
                                                :class="copied ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700'"
                                            >
                                                <span x-text="copied ? '✓ Tersalin' : 'Salin'"></span>
                                            </button>
                                            @if($order->notes)
                                                <span class="text-xs text-slate-400 italic truncate max-w-xs" title="{{ $order->notes }}">
                                                    • "{{ $order->notes }}"
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Middle: Match Progress Counter HUD -->
                                <div class="shrink-0 w-full lg:w-56 p-2.5 rounded-xl bg-slate-950/70 border border-slate-800/80">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="text-[11px] font-bold text-slate-400">SISA GAME:</span>
                                        <span class="font-black font-mono text-amber-400 text-sm">
                                            {{ $order->remaining_matches }} <span class="text-xs text-slate-500">/ {{ $order->total_matches }}</span>
                                        </span>
                                    </div>
                                    <div class="w-full h-2 rounded-full bg-slate-900 overflow-hidden border border-slate-800">
                                        <div 
                                            class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-emerald-400 transition-all duration-300"
                                            style="width: {{ $order->progress_percentage }}%"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Right: Decrement & Manage Buttons -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <!-- 1-Click Decrement -->
                                    <button 
                                        wire:click="decrementMatch({{ $order->id }})" 
                                        wire:loading.attr="disabled"
                                        class="py-2.5 px-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs flex items-center gap-1.5 shadow-md shadow-emerald-950/50 transition active:scale-95 disabled:opacity-50"
                                        title="Kurangi 1 match setelah game selesai"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" />
                                        </svg>
                                        <span>-1 GAME</span>
                                    </button>

                                    <!-- Add Match (+N) -->
                                    <button 
                                        wire:click="openAddMatchModal({{ $order->id }})" 
                                        class="py-2.5 px-3 rounded-xl bg-blue-500/15 hover:bg-blue-500/25 border border-blue-500/40 text-blue-300 font-bold text-xs flex items-center gap-1 transition active:scale-95"
                                        title="Tambah match ke order ini"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>+Match</span>
                                    </button>

                                    <!-- Finish -->
                                    <button 
                                        wire:click="completeOrder({{ $order->id }})" 
                                        wire:confirm="Yakin ingin langsung menyelesaikan order ini secara penuh?"
                                        class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition"
                                        title="Selesaikan Langsung"
                                    >
                                        ✓
                                    </button>

                                    <!-- Log -->
                                    <button 
                                        wire:click="viewLogs({{ $order->id }})" 
                                        class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition"
                                        title="Riwayat Log"
                                    >
                                        📝
                                    </button>

                                    <!-- Cancel -->
                                    <button 
                                        wire:click="cancelOrder({{ $order->id }})" 
                                        wire:confirm="Yakin ingin membatalkan order ini?"
                                        class="p-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-semibold transition"
                                        title="Batalkan"
                                    >
                                        ✕
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach

                    <!-- 2. QUEUED WAITING PLAYERS (ANTREAN MENUNGGU BERIKUTNYA) -->
                    @foreach ($queuedOrders as $index => $order)
                        <div class="p-3.5 rounded-2xl transition flex flex-col lg:flex-row lg:items-center justify-between gap-3.5 {{ $order->is_fast_track ? 'bg-gradient-to-r from-amber-950/40 via-slate-900 to-slate-900 border-2 border-amber-500/60 shadow-lg shadow-amber-950/30' : 'bg-slate-900/90 border border-slate-800 hover:border-slate-700' }}">
                            
                            <!-- Left: Queue Number, Name & ID -->
                            <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl font-black text-sm flex items-center justify-center shrink-0 border {{ $order->is_fast_track ? 'bg-amber-500 text-slate-950 border-amber-400 font-extrabold shadow-md shadow-amber-500/30' : 'bg-slate-800 text-amber-400 border-slate-700' }}">
                                    #{{ $index + 1 }}
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($order->is_fast_track)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500/25 text-amber-300 border border-amber-500/50 flex items-center gap-1 shadow-sm">
                                                <span>⚡</span> FAST TRACK
                                            </span>
                                        @endif
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $order->game->name }}
                                        </span>
                                        <h4 class="font-bold text-white text-sm truncate">
                                            {{ $order->customer_name }}
                                        </h4>
                                        <span class="text-xs text-slate-400 font-mono">
                                            {{ $order->order_number }}
                                        </span>
                                    </div>

                                    <div x-data="{ copied: false }" class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-mono text-slate-300 bg-slate-950 px-2 py-0.5 rounded border border-slate-800/80 select-all">
                                            ID: {{ $order->in_game_id }}
                                        </span>
                                        <button 
                                            @click="navigator.clipboard.writeText('{{ $order->in_game_id }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="text-[11px] font-bold transition px-2 py-0.5 rounded"
                                            :class="copied ? 'bg-emerald-500 text-slate-950' : 'text-slate-400 hover:text-white bg-slate-800'"
                                        >
                                            <span x-text="copied ? '✓' : 'Salin'"></span>
                                        </button>
                                        @if($order->notes)
                                            <span class="text-xs text-slate-400 italic truncate max-w-xs" title="{{ $order->notes }}">
                                                • "{{ $order->notes }}"
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Middle & Right: Info & Action -->
                            <div class="flex items-center justify-between lg:justify-end gap-4 pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-800/80">
                                <div class="text-left lg:text-right">
                                    <span class="text-xs font-bold {{ $order->is_fast_track ? 'text-amber-300 font-black' : 'text-amber-400' }} block font-mono">
                                        {{ $order->total_matches }} Match ({{ $order->formatted_amount }})
                                    </span>
                                    <span class="text-[10px] text-slate-500">
                                        {{ $order->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- Mulai Main Button -->
                                    <button 
                                        wire:click="setPlaying({{ $order->id }})" 
                                        class="px-3.5 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs flex items-center gap-1.5 shadow-md shadow-cyan-500/20 transition active:scale-95"
                                        title="Panggil player ini ke status Sedang Main"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                        <span>Mulai Main</span>
                                    </button>

                                    <!-- Add Match (+N) -->
                                    <button 
                                        wire:click="openAddMatchModal({{ $order->id }})" 
                                        class="px-2.5 py-2 rounded-xl bg-blue-500/15 hover:bg-blue-500/25 border border-blue-500/40 text-blue-300 font-bold text-xs flex items-center gap-1 transition active:scale-95"
                                        title="Tambah match ke order ini"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>+Match</span>
                                    </button>

                                    <!-- Toggle Fast Track VIP Button -->
                                    <button 
                                        wire:click="toggleFastTrack({{ $order->id }})" 
                                        class="p-2 rounded-xl text-xs font-bold transition {{ $order->is_fast_track ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/30' : 'bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-amber-300' }}"
                                        title="{{ $order->is_fast_track ? 'Turunkan ke Antrean Reguler' : 'Naikkan ke Fast Track VIP' }}"
                                    >
                                        ⚡
                                    </button>

                                    <!-- Log -->
                                    <button 
                                        wire:click="viewLogs({{ $order->id }})" 
                                        class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition"
                                        title="Riwayat Log"
                                    >
                                        📝
                                    </button>

                                    <!-- Batal -->
                                    <button 
                                        wire:click="cancelOrder({{ $order->id }})" 
                                        wire:confirm="Yakin ingin membatalkan antrean ini?"
                                        class="p-2 rounded-xl bg-slate-800 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 transition"
                                        title="Batalkan Antrean"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            @endif
        </section>

        <!-- SECTION 3: RIWAYAT ORDER SELESAI -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold tracking-tight text-slate-400 uppercase">
                    🏁 Riwayat 10 Match Terakhir yang Selesai
                </h2>
            </div>

            @if ($completedOrders->isEmpty())
                <div class="p-4 rounded-xl border border-slate-800/60 bg-slate-900/20 text-center text-xs text-slate-500">
                    Belum ada riwayat match yang selesai pada sesi ini.
                </div>
            @else
                <div class="rounded-2xl border border-slate-800 overflow-hidden bg-slate-900/40">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400 uppercase font-semibold">
                            <tr>
                                <th class="p-3.5">Order</th>
                                <th class="p-3.5">Player / In-Game ID</th>
                                <th class="p-3.5">Game</th>
                                <th class="p-3.5">Total Match</th>
                                <th class="p-3.5">Waktu Selesai</th>
                                <th class="p-3.5 text-right">Audit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300">
                            @foreach ($completedOrders as $order)
                                <tr class="hover:bg-slate-850/50 transition">
                                    <td class="p-3.5 font-mono text-slate-400">{{ $order->order_number }}</td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-white">{{ $order->customer_name }}</div>
                                        <div class="font-mono text-slate-400 text-[11px]">{{ $order->in_game_id }}</div>
                                    </td>
                                    <td class="p-3.5 font-medium">{{ $order->game->name }}</td>
                                    <td class="p-3.5 font-bold text-emerald-400">{{ $order->total_matches }} Game (Lunas)</td>
                                    <td class="p-3.5 text-slate-400">{{ $order->completed_at ? $order->completed_at->format('H:i:s d M') : '-' }}</td>
                                    <td class="p-3.5 text-right flex items-center justify-end gap-3">
                                        <button 
                                            wire:click="openAddMatchModal({{ $order->id }})" 
                                            class="text-xs text-blue-400 hover:text-blue-300 font-semibold flex items-center gap-1"
                                            title="Main lagi / Tambah match"
                                        >
                                            <span>+Match</span>
                                        </button>
                                        <button 
                                            wire:click="viewLogs({{ $order->id }})" 
                                            class="text-xs text-cyan-400 hover:underline font-semibold"
                                        >
                                            Log
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>

    <!-- ================= MODALS ================= -->

    <!-- Modal Tambah Antrean Manual -->
    @if ($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-black text-white">Tambah Antrean Baru (Manual)</h3>
                    <button wire:click="$set('showAddModal', false)" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <form wire:submit="createManualOrder">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Pilih Game</label>
                            <select wire:model.live="new_game_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                                @foreach($games as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }} (Rp {{ number_format($g->price_per_match) }}/match)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Donatur / Pemesan</label>
                                <input type="text" wire:model="new_customer_name" required placeholder="Contoh: Aldi" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">In-Game ID / Server</label>
                                <input type="text" wire:model="new_in_game_id" required placeholder="Contoh: 12345678(2021)" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Total Match / Game</label>
                                <input type="number" wire:model.live="new_total_matches" min="1" max="50" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nominal Pembayaran (Rp)</label>
                                <input type="number" wire:model="new_amount_paid" min="0" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Catatan Khusus (Opsional)</label>
                            <input type="text" wire:model="new_notes" placeholder="Contoh: Request hero Saber / Gendong ke Mythic" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <!-- Toggle Fast Track VIP -->
                        <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-between gap-3">
                            <div>
                                <div class="text-xs font-bold text-amber-300 flex items-center gap-1.5">
                                    <span>⚡ Jalur Cepat (Fast Track VIP)</span>
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Otomatis meloncat ke nomor antrean paling depan
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="new_is_fast_track" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showAddModal', false)" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20">Simpan ke Antrean</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Simulasi Donasi Saweria -->
    @if ($showSimulateSaweriaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl bg-slate-900 border border-amber-500/40 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 font-bold flex items-center justify-center">⚡</div>
                        <h3 class="text-lg font-black text-amber-300">Simulasi Webhook Saweria</h3>
                    </div>
                    <button wire:click="$set('showSimulateSaweriaModal', false)" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <p class="text-xs text-slate-400 mb-5">
                    Fitur ini mensimulasikan donasi masuk dari Saweria secara nyata. Sistem akan membaca format pesan dengan regex dan mengalkulasi jumlah game sesuai harga per match.
                </p>

                <form wire:submit="simulateSaweria">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Donatur di Saweria</label>
                            <input type="text" wire:model="saweria_donator" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nominal Donasi (Rp)</label>
                            <input type="number" wire:model="saweria_amount" step="5000" min="5000" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Pesan Donasi</label>
                            <textarea wire:model="saweria_message" rows="3" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 font-mono"></textarea>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Reguler: <code class="text-cyan-400">MABAR [GAME] [ID_GAME]</code> • Fast Track VIP: <code class="text-amber-400 font-bold">MABAR FAST [GAME] [ID_GAME]</code>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showSimulateSaweriaModal', false)" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/20">Kirim Donasi Simulasi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Pengaturan Streamer -->
    @if ($showSettingsModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-white">Pengaturan Streamer & Channel</h3>
                    <button wire:click="$set('showSettingsModal', false)" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <form wire:submit="saveSettings">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Streamer</label>
                            <input type="text" wire:model="streamer_name" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Teks Pengumuman Running Teks</label>
                            <input type="text" wire:model="announcement" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Link Channel (YouTube / TikTok / Twitch)</label>
                            <input type="url" wire:model="channel_url" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200">
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800">
                            <label class="block text-xs font-bold text-purple-400 uppercase mb-1">URL Browser Source OBS</label>
                            <input type="text" readonly value="{{ url('/overlay/' . $setting->overlay_token) }}" class="w-full bg-slate-900 border border-slate-800 rounded-lg p-2 text-xs text-slate-300 font-mono select-all">
                            <p class="text-[10px] text-slate-500 mt-1">Copy URL ini dan tempelkan ke OBS -> Add Source -> Browser.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showSettingsModal', false)" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs">Tutup</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-lg shadow-cyan-500/20">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Tambah Match (+N) -->
    @if ($showAddMatchModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-3xl bg-slate-900 border border-blue-500/40 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 font-bold flex items-center justify-center">
                            +
                        </div>
                        <div>
                            <h3 class="text-base font-black text-white">Tambah Match Ekstra</h3>
                            <p class="text-xs text-blue-400 font-mono">{{ $addMatchOrderLabel }}</p>
                        </div>
                    </div>
                    <button wire:click="closeAddMatchModal" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <p class="text-xs text-slate-400 mb-5">
                    Tambahkan match ke order ini. Jika order sudah selesai, status akan otomatis aktif kembali ke <span class="text-cyan-400 font-semibold">Sedang Main</span>.
                </p>

                <form wire:submit="addMatchToOrder">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Jumlah Match yang Ditambahkan</label>
                            <div class="flex items-center gap-2 mb-2">
                                @foreach([1, 2, 3, 5] as $cnt)
                                    <button 
                                        type="button" 
                                        wire:click="$set('add_match_count', {{ $cnt }}); $set('add_match_amount', {{ $cnt * $add_match_unit_price }})"
                                        class="flex-1 py-1.5 rounded-lg text-xs font-bold transition {{ $add_match_count === $cnt ? 'bg-blue-500 text-slate-950 font-black shadow-sm' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}"
                                    >
                                        +{{ $cnt }}
                                    </button>
                                @endforeach
                            </div>
                            <input 
                                type="number" 
                                wire:model.live="add_match_count" 
                                min="1" 
                                max="50" 
                                required 
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 font-mono"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nominal Pembayaran Tambahan (Rp)</label>
                            <input 
                                type="number" 
                                wire:model="add_match_amount" 
                                min="0" 
                                required 
                                class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 font-mono"
                            >
                            <p class="text-[11px] text-slate-500 mt-1">
                                Tarif standar: Rp {{ number_format($add_match_unit_price, 0, ',', '.') }}/match. Isi 0 jika match gratis/bonus.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <button type="button" wire:click="closeAddMatchModal" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-semibold text-xs">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-400 text-slate-950 font-bold text-xs shadow-lg shadow-blue-500/20">Simpan & Tambah Match</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Riwayat Log Audit -->
    @if ($selectedOrderLogs)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-lg rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-black text-white">Log Audit: {{ $selectedOrderLogs->customer_name }}</h3>
                        <p class="text-xs text-slate-400 font-mono">{{ $selectedOrderLogs->order_number }} • ID: {{ $selectedOrderLogs->in_game_id }}</p>
                    </div>
                    <button wire:click="closeLogs" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @forelse($selectedOrderLogs->matchLogs as $log)
                        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs">
                            <div class="flex items-center justify-between font-bold mb-1">
                                <span class="text-cyan-400">{{ $log->action }}</span>
                                <span class="text-slate-500 font-mono text-[10px]">{{ $log->created_at->format('H:i:s • d M Y') }}</span>
                            </div>
                            <div class="text-slate-300">{{ $log->remarks }}</div>
                            <div class="mt-1 text-[11px] text-slate-500 font-mono">
                                Sisa match: {{ $log->matches_before }} ➔ {{ $log->matches_after }} (Actor: {{ $log->actor_name }})
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">Belum ada catatan log.</p>
                    @endforelse
                </div>

                <div class="mt-5 text-right">
                    <button wire:click="closeLogs" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-200 text-xs font-semibold">Tutup</button>
                </div>
            </div>
        </div>
    @endif

</div>
