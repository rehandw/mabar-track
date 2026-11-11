<div wire:poll.2s class="p-4 w-96 max-w-full select-none">
    
    <!-- Main Widget Container -->
    <div class="rounded-3xl bg-slate-950/90 border-2 border-cyan-500/40 shadow-2xl shadow-cyan-950/80 backdrop-blur-md overflow-hidden glow-cyan">
        
        <!-- Header Banner -->
        <div class="px-4 py-3 bg-gradient-to-r from-slate-900 to-slate-950 border-b border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full {{ $setting->is_queue_open ? 'bg-emerald-400 animate-ping' : 'bg-rose-500' }}"></span>
                <span class="text-xs font-black uppercase tracking-wider text-white">MABAR HUD</span>
            </div>
            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full {{ $setting->is_queue_open ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-300 border border-rose-500/40' }}">
                {{ $setting->is_queue_open ? 'QUEUE OPEN' : 'QUEUE CLOSED' }}
            </span>
        </div>

        <!-- Section 1: NOW PLAYING (Active Player) -->
        <div class="p-4 border-b border-slate-800/80 bg-gradient-to-b from-cyan-950/20 to-transparent">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-cyan-400 flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    SEDANG MAIN
                </span>
                @if($activeOrder)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700">
                        {{ $activeOrder->game->name }}
                    </span>
                @endif
            </div>

            @if($activeOrder)
                <div class="space-y-2">
                    <div>
                        <div class="text-lg font-black text-white leading-tight truncate">
                            {{ $activeOrder->customer_name }}
                        </div>
                        <div class="text-xs font-mono font-bold text-cyan-300 truncate">
                            ID: {{ $activeOrder->in_game_id }}
                        </div>
                    </div>

                    <!-- Match Counter Display -->
                    <div class="p-2.5 rounded-xl bg-slate-900/90 border border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400">SISA GAME:</span>
                        <div class="text-right">
                            <span class="text-base font-black font-mono text-amber-400 tracking-wider">
                                {{ $activeOrder->remaining_matches }} <span class="text-xs text-slate-500">/ {{ $activeOrder->total_matches }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="w-full h-1.5 rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-cyan-400 to-emerald-400 transition-all duration-300" style="width: {{ $activeOrder->progress_percentage }}%"></div>
                    </div>
                </div>
            @else
                <div class="py-3 text-center text-xs text-slate-400 italic">
                    Belum ada player di lobby
                </div>
            @endif
        </div>

        <!-- Section 2: UP NEXT (Next 3 in Queue) -->
        <div class="p-4 bg-slate-950/60">
            <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2.5">
                ANTREAN BERIKUTNYA
            </div>

            @if($nextQueued->isEmpty())
                <div class="py-2 text-center text-xs text-slate-500 italic">
                    Antrean kosong
                </div>
            @else
                <div class="space-y-2">
                    @foreach($nextQueued as $idx => $item)
                        <div class="flex items-center justify-between p-2 rounded-xl text-xs {{ $item->is_fast_track ? 'bg-amber-950/30 border border-amber-500/50' : 'bg-slate-900/70 border border-slate-800/80' }}">
                            <div class="flex items-center gap-2 truncate pr-2">
                                <span class="w-5 h-5 rounded-md {{ $item->is_fast_track ? 'bg-amber-500 text-slate-950' : 'bg-slate-800 text-amber-400' }} font-extrabold text-[10px] flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="truncate">
                                    <div class="font-bold text-white truncate flex items-center gap-1">
                                        @if($item->is_fast_track)
                                            <span class="text-[9px] px-1 rounded bg-amber-500/20 text-amber-300 font-extrabold border border-amber-500/40">⚡ VIP</span>
                                        @endif
                                        <span class="truncate">{{ $item->customer_name }}</span>
                                    </div>
                                    <div class="text-[10px] font-mono text-slate-400 truncate">{{ $item->in_game_id }}</div>
                                </div>
                            </div>
                            <span class="text-[11px] font-bold {{ $item->is_fast_track ? 'text-amber-300 font-black' : 'text-slate-300' }} font-mono shrink-0">
                                {{ $item->total_matches }} Match
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Bottom Announcement Ticker -->
        @if($setting->announcement)
            <div class="px-3 py-2 bg-slate-900 border-t border-slate-800 text-[10px] text-slate-300 flex items-center gap-2">
                <span class="font-bold text-cyan-400 shrink-0">INFO:</span>
                <span class="truncate">{{ $setting->announcement }}</span>
            </div>
        @endif

    </div>

</div>
