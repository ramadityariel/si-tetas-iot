@extends('layouts.admin')
@section('title', __('admin.users.title') . ' - Si-Tetas Admin')

@section('content')
<div x-data="{ showModal: false, editMode: false, userForm: { id: '', name: '', email: '', role: 'admin' } }" class="p-8 max-w-[1440px] mx-auto relative z-10">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-[#194A63] dark:text-white font-headline tracking-tight drop-shadow-sm">{{ __('admin.users.title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ __('admin.users.subtitle') }}</p>
            </div>
            <button @click="showModal = true; editMode = false; userForm = { id: '', name: '', email: '', role: 'admin' }" class="bg-[#35627C] dark:bg-sky-600 hover:opacity-90 text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <span class="material-symbols-outlined">person_add</span>
                {{ __('admin.users.add_admin') }}
            </button>
        </div>
        
        <!-- Bento Stats Grid (Contextual) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total Admin Card -->
            <div class="group relative overflow-hidden bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex items-center gap-5 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(56,189,248,0.1)] dark:hover:border-sky-500/30">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-sky-50 dark:bg-sky-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                <div class="relative z-10 h-14 w-14 rounded-full bg-sky-100 dark:bg-sky-500/20 flex items-center justify-center text-[#194A63] dark:text-sky-400 border border-sky-200 dark:border-sky-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                    <span class="material-symbols-outlined text-2xl">group</span>
                </div>
                <div class="relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider group-hover:text-[#194A63] dark:group-hover:text-sky-300 transition-colors">{{ __('admin.users.total_admin') }}</p>
                    <p class="text-3xl font-black text-[#194A63] dark:text-white mt-0.5">{{ $users->count() }} <span class="text-base font-bold text-slate-400 dark:text-slate-500">{{ __('admin.users.personnel') }}</span></p>
                </div>
            </div>
            
            <!-- Active Today Card -->
            <div class="group relative overflow-hidden bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 p-6 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl flex items-center gap-5 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(52,211,153,0.1)] dark:hover:border-emerald-500/30">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 opacity-0 group-hover:opacity-100"></div>
                <div class="relative z-10 h-14 w-14 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 group-hover:scale-110 group-hover:rotate-[-6deg] transition-all duration-300">
                    <span class="material-symbols-outlined text-2xl">verified_user</span>
                </div>
                <div class="relative z-10 transform group-hover:translate-x-1 transition-transform duration-300">
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">{{ __('admin.users.active_today') }}</p>
                    <p class="text-3xl font-black text-[#194A63] dark:text-white mt-0.5">{{ $users->count() }} <span class="text-base font-bold text-slate-400 dark:text-slate-500">{{ __('admin.users.personnel') }}</span></p>
                </div>
            </div>
        </div>
        
        <!-- Table Card -->
        <div class="bg-white dark:bg-slate-900/40 backdrop-blur-md border border-slate-100 dark:border-white/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-2xl overflow-hidden transition-all duration-300">
            <div class="p-6 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-[#194A63] dark:text-white text-lg font-headline">{{ __('admin.users.admin_list') }}</h3>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input class="pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-sky-500/50 text-slate-800 dark:text-white placeholder:text-slate-400 w-64 shadow-sm" placeholder="{{ __('admin.users.search_ph') }}" type="text"/>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/5 text-slate-500 dark:text-slate-300 text-xs font-bold uppercase tracking-widest">
                            <th class="px-8 py-4">{{ __('admin.users.col_name') }}</th>
                            <th class="px-8 py-4">{{ __('admin.users.col_email') }}</th>
                            <th class="px-8 py-4">{{ __('admin.users.col_date') }}</th>
                            <th class="px-8 py-4 text-right">{{ __('admin.users.col_action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-[#35627C]/10 dark:bg-sky-500/20 flex items-center justify-center text-[#194A63] dark:text-sky-400 font-bold border border-[#35627C]/20 dark:border-sky-500/30">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-700 dark:text-slate-200 block">{{ $user->name }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $user->role }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
                            <td class="px-8 py-5 text-slate-500 dark:text-slate-400 text-sm font-medium">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button @click="showModal = true; editMode = true; userForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' }" class="text-slate-400 hover:text-[#194A63] dark:hover:text-sky-400 transition-colors p-1"><span class="material-symbols-outlined">edit</span></button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengguna ini?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 dark:hover:text-red-400 transition-colors p-1 flex items-center"><span class="material-symbols-outlined">delete</span></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-500 dark:text-slate-400 font-bold">{{ __('admin.users.no_data') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-slate-100 dark:border-white/10 flex justify-between items-center text-sm text-slate-500 dark:text-slate-400">
                <p>{{ __('admin.users.showing') }} {{ $users->count() }} {{ strtolower(__('admin.users.personnel')) }}</p>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-md hover:bg-slate-50 dark:hover:bg-white/10 disabled:opacity-50 transition-colors" disabled="">{{ __('admin.users.prev') }}</button>
                    <button class="px-3 py-1 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-md hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">{{ __('admin.users.next') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Mockup: Tambah Admin -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="showModal = false" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <!-- Modal Content -->
        <div x-show="showModal" x-transition class="relative bg-white dark:bg-slate-900 border border-slate-100 dark:border-white/10 w-full max-w-md rounded-2xl shadow-[0_24px_48px_rgba(0,0,0,0.2)] overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-[#194A63] dark:text-white font-headline" x-text="editMode ? '{{ __('admin.users.edit_title') }}' : '{{ __('admin.users.add_title') }}'"></h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1" x-text="editMode ? '{{ __('admin.users.edit_desc') }}' : '{{ __('admin.users.add_desc') }}'"></p>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors bg-slate-100 dark:bg-white/10 rounded-full p-1 border border-slate-200 dark:border-white/10">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form :action="editMode ? '/admin/users/' + userForm.id : '{{ route('users.store') }}'" method="POST" class="space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">{{ __('admin.users.full_name') }}</label>
                        <input name="name" x-model="userForm.name" required class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 focus:ring-2 focus:ring-sky-500/50 text-slate-800 dark:text-white placeholder:text-slate-400 text-sm transition-all shadow-sm" placeholder="{{ __('admin.users.name_ph') }}" type="text"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">{{ __('admin.users.col_email') }}</label>
                        <input name="email" x-model="userForm.email" required class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 focus:ring-2 focus:ring-sky-500/50 text-slate-800 dark:text-white placeholder:text-slate-400 text-sm transition-all shadow-sm" placeholder="contoh@sitetas.id" type="email"/>
                    </div>
                    <div x-show="editMode">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">{{ __('admin.users.role') }}</label>
                        <select name="role" x-model="userForm.role" :required="editMode" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 focus:ring-2 focus:ring-sky-500/50 text-slate-800 dark:text-white text-sm transition-all shadow-sm cursor-pointer">
                            <option value="admin">{{ __('admin.users.role') }}</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">{{ __('admin.users.password') }} <span x-show="editMode" class="text-slate-400 dark:text-slate-500 font-normal lowercase">{{ __('admin.users.pass_empty') }}</span></label>
                        <div class="relative">
                            <input name="password" :required="!editMode" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl py-3 px-4 focus:ring-2 focus:ring-sky-500/50 text-slate-800 dark:text-white placeholder:text-slate-400 text-sm transition-all shadow-sm" placeholder="{{ __('admin.users.pass_ph') }}" type="password"/>
                        </div>
                    </div>
                    <div class="pt-4 flex flex-col gap-3">
                        <button class="w-full bg-[#35627C] dark:bg-sky-600 hover:opacity-90 text-white py-4 rounded-xl font-bold shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2" type="submit">
                            <span class="material-symbols-outlined text-lg">save</span>
                            {{ __('admin.users.save') }}
                        </button>
                        <button @click="showModal = false" class="w-full bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-white border border-slate-200 dark:border-white/10 py-3 rounded-xl font-semibold hover:bg-slate-200 dark:hover:bg-white/20 transition-all text-sm" type="button">
                            {{ __('admin.users.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
