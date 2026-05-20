@extends('layouts.admin')
@section('title', 'Editor Artikel - Si-Tetas Admin')

@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px;
        background-color: #f8fafc;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .ql-container.ql-snow {
        border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .ql-editor {
        min-height: 450px;
        font-size: 1rem;
        padding: 2rem;
        color: #475569;
    }
    .ql-editor.ql-blank::before {
        color: #94a3b8;
        font-style: normal;
    }
</style>

<div class="p-8 max-w-5xl mx-auto w-full">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-headline font-extrabold text-primary tracking-tight mb-2">Tulis Artikel Baru</h2>
            <p class="text-slate-500 font-body">Bagikan pengetahuan terbaru tentang teknologi inkubasi pintar.</p>
        </div>
        <a href="{{ route('admin.blog') }}" class="text-sm font-semibold text-primary hover:underline font-body shrink-0">← Kembali ke daftar</a>
    </div>

    <form id="blogForm" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(25,47,63,0.04)] space-y-4">
            <div>
                <input name="title" value="{{ old('title') }}" class="w-full bg-transparent border-none text-3xl font-headline font-bold text-primary placeholder:text-slate-400 focus:ring-0 p-0 @error('title') ring-2 ring-error/40 rounded @enderror" placeholder="Masukkan Judul Artikel..." type="text" required/>
                @error('title')<p class="text-error text-xs mt-1 font-body">{{ $message }}</p>@enderror
            </div>
            <div>
                <input name="subtitle" value="{{ old('subtitle') }}" class="w-full bg-transparent border-none text-lg font-body text-slate-600 placeholder:text-slate-400 focus:ring-0 p-0" placeholder="Ringkasan atau Subjudul (opsional)..." type="text"/>
                @error('subtitle')<p class="text-error text-xs mt-1 font-body">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_24px_rgba(25,47,63,0.04)] overflow-hidden @error('content') ring-2 ring-error/40 @enderror">
                    <div id="editor-container"></div>
                </div>
                <input type="hidden" name="content" id="content">
                @error('content')<p class="text-error text-xs font-body">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-6">
                <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(25,47,63,0.04)]">
                    <h3 class="text-sm font-bold text-primary mb-4 font-headline uppercase tracking-wider">Thumbnail</h3>
                    <p class="text-xs text-slate-500 font-body mb-3">Wajib untuk artikel baru (JPEG, PNG, GIF, WebP, maks. 5 MB).</p>
                    <div id="upload-prompt">
                        <div id="thumbnail-dropzone" class="border-2 border-dashed border-slate-300 rounded-xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-surface-container-low transition-colors group mb-4 @error('thumbnail') border-error/50 @enderror">
                            <span class="material-symbols-outlined text-4xl text-slate-400 group-hover:text-primary transition-colors mb-3">add_photo_alternate</span>
                            <p class="text-xs font-semibold text-slate-500">Unggah Thumbnail</p>
                            <p class="text-[10px] text-slate-400 mt-1">Saran: 1200x630px (maks. 5 MB)</p>
                            <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp" class="hidden">
                        </div>
                    </div>
                    <img id="preview-image" class="hidden w-full h-auto rounded-lg object-cover cursor-pointer" alt="Pratinjau thumbnail">
                    @error('thumbnail')<p class="text-error text-xs mt-2 font-body">{{ $message }}</p>@enderror

                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">Caption / Keterangan Gambar</label>
                        <input name="caption" value="{{ old('caption') }}" class="w-full bg-surface-container-low border-none rounded-lg text-sm font-medium py-2 px-3 focus:ring-2 focus:ring-primary/20" placeholder="Keterangan gambar (opsional)..." type="text"/>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_8px_24px_rgba(25,47,63,0.04)]">
                    <h3 class="text-sm font-bold text-primary mb-4 font-headline uppercase tracking-wider">Pengaturan</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[11px] font-bold text-slate-400 uppercase mb-1 block">Kategori</label>
                            @php $cat = old('category', 'Teknologi'); @endphp
                            <select name="category" class="w-full bg-surface-container-low border-none rounded-lg text-sm font-medium py-2 px-3 focus:ring-2 focus:ring-primary/20 @error('category') ring-2 ring-error/40 @enderror">
                                <option value="Teknologi" @selected($cat === 'Teknologi')>Teknologi</option>
                                <option value="Tips & Trik" @selected($cat === 'Tips & Trik')>Tips &amp; Trik</option>
                                <option value="Studi Kasus" @selected($cat === 'Studi Kasus')>Studi Kasus</option>
                            </select>
                            @error('category')<p class="text-error text-xs mt-1 font-body">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 flex justify-end items-center gap-4 pb-12">
            <button type="submit" name="action" value="draft" class="px-8 py-3 rounded-xl border-2 border-primary-container text-primary-container font-bold text-sm hover:bg-primary-container/5 transition-all shadow-[0_8px_24px_rgba(25,47,63,0.04)] active:scale-95">
                Simpan Draft
            </button>
            <button type="submit" name="action" value="publish" class="px-10 py-3 rounded-xl bg-[#35627C] text-white font-bold text-sm hover:opacity-90 transition-all shadow-lg active:scale-95">
                Terbitkan
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Mulai menulis artikel Anda di sini...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'header': [2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'blockquote']
                ]
            }
        });

        var initialContent = @json(old('content', ''));
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }

        var dropzone = document.getElementById('thumbnail-dropzone');
        var fileInput = document.getElementById('thumbnail');
        if (dropzone && fileInput) {
            dropzone.addEventListener('click', function() {
                fileInput.click();
            });
        }

        var form = document.getElementById('blogForm');
        var clickedButton = null;
        var submitButtons = form.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                clickedButton = this.value;
            });
        });

        if (form) {
            form.addEventListener('submit', function(e) {
                var thumbnailInput = document.getElementById('thumbnail');
                if (clickedButton === 'publish' && thumbnailInput.files.length === 0) {
                    e.preventDefault();
                    alert('Harap tambahkan thumbnail sebelum menerbitkan artikel!');
                    return false;
                }
                document.getElementById('content').value = quill.root.innerHTML;
            });
        }

        var thumbnailInput = document.getElementById('thumbnail');
        var previewImage = document.getElementById('preview-image');
        var uploadPrompt = document.getElementById('upload-prompt');

        if (thumbnailInput && previewImage && uploadPrompt) {
            thumbnailInput.addEventListener('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    previewImage.src = URL.createObjectURL(file);
                    previewImage.classList.remove('hidden');
                    uploadPrompt.classList.add('hidden');
                }
            });

            previewImage.addEventListener('click', function() {
                thumbnailInput.click();
            });
        }
    });
</script>
@endsection
