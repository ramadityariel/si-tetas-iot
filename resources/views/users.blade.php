@extends('layouts.admin')
@section('title', 'Manajemen Pengguna - Si-Tetas Admin')

@section('content')
<div x-data="{ showModal: false, editMode: false, userForm: { id: '', name: '', email: '', role: 'admin' } }" class="p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight">Manajemen Pengguna</h2>
                <p class="text-slate-500 mt-1">Kelola akses administrator dan tim operasional inkubator.</p>
            </div>
            <button @click="showModal = true; editMode = false; userForm = { id: '', name: '', email: '', role: 'admin' }" class="bg-primary hover:bg-primary-container text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
                <span class="material-symbols-outlined">person_add</span>
                Tambah Admin
            </button>
        </div>
        
        <!-- Bento Stats Grid (Contextual) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Admin</p>
                    <p class="text-2xl font-bold text-primary">{{ $users->count() }} Personel</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Aktif Hari Ini</p>
                    <p class="text-2xl font-bold text-secondary">{{ $users->count() }} Personel</p>
                </div>
            </div>
        </div>
        
        <!-- Table Card -->
        <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-primary text-lg">Daftar Admin</h3>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 w-64" placeholder="Cari nama atau email..." type="text"/>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low text-slate-500 text-xs font-bold uppercase tracking-widest">
                            <th class="px-8 py-4">Nama Administrator</th>
                            <th class="px-8 py-4">Alamat Email</th>
                            <th class="px-8 py-4">Tanggal Bergabung</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-700 block">{{ $user->name }}</span>
                                        <span class="text-xs text-slate-500">{{ $user->role }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-slate-600">{{ $user->email }}</td>
                            <td class="px-8 py-5 text-slate-500 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button @click="showModal = true; editMode = true; userForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' }" class="text-slate-400 hover:text-primary transition-colors p-1"><span class="material-symbols-outlined">edit</span></button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengguna ini?');" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-error transition-colors p-1 flex items-center"><span class="material-symbols-outlined">delete</span></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-slate-500">Belum ada data admin.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-surface-container-low/30 flex justify-between items-center text-sm text-slate-500">
                <p>Menampilkan {{ $users->count() }} admin</p>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-md hover:bg-slate-50 disabled:opacity-50" disabled="">Sebelumnya</button>
                    <button class="px-3 py-1 bg-white border border-slate-200 rounded-md hover:bg-slate-50">Berikutnya</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Mockup: Tambah Admin -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="showModal = false" x-transition.opacity class="absolute inset-0 bg-primary/20 backdrop-blur-sm"></div>
        
        <!-- Modal Content -->
        <div x-show="showModal" x-transition class="relative bg-surface-container-lowest w-full max-w-md rounded-xl shadow-[0_24px_48px_rgba(25,47,63,0.15)] overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-primary font-headline" x-text="editMode ? 'Edit Admin' : 'Tambah Admin Baru'"></h3>
                        <p class="text-slate-500 text-sm" x-text="editMode ? 'Perbarui data admin yang ada.' : 'Daftarkan personel baru ke dalam sistem.'"></p>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form :action="editMode ? '/admin/users/' + userForm.id : '{{ route('users.store') }}'" method="POST" class="space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input name="name" x-model="userForm.name" required class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400 text-sm transition-all" placeholder="Masukkan nama lengkap" type="text"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
                        <input name="email" x-model="userForm.email" required class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400 text-sm transition-all" placeholder="contoh@sitetas.id" type="email"/>
                    </div>
                    <div x-show="editMode">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Role</label>
                        <select name="role" x-model="userForm.role" :required="editMode" class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 text-sm transition-all">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Password <span x-show="editMode" class="text-slate-400 font-normal lowercase">(kosongkan jika tidak diubah)</span></label>
                        <div class="relative">
                            <input name="password" :required="!editMode" class="w-full bg-surface-container-high border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400 text-sm transition-all" placeholder="Minimal 8 karakter" type="password"/>
                        </div>
                    </div>
                    <div class="pt-4 flex flex-col gap-3">
                        <button class="w-full bg-primary hover:bg-primary-container text-white py-4 rounded-full font-bold shadow-lg transition-all active:scale-95 flex justify-center items-center gap-2" type="submit">
                            <span class="material-symbols-outlined text-lg">save</span>
                            Simpan Data Admin
                        </button>
                        <button @click="showModal = false" class="w-full bg-transparent text-slate-500 py-3 rounded-full font-semibold hover:bg-slate-50 transition-all text-sm" type="button">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
            

        </div>
    </div>
</div>
@endsection
