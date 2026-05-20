<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('author');
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        $articles = $query->latest()->get();
        return view('blog', compact('articles'));
    }

    public function create()
    {
        return view('blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $text = trim(preg_replace('/\s|&nbsp;/u', '', strip_tags((string) $value)));
                    if ($text === '') {
                        $fail('Isi artikel tidak boleh kosong.');
                    }
                },
            ],
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'required|string|max:255',
        ], [
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5 MB. Kompres gambar atau pilih file yang lebih kecil.',
        ]);

        $file = $request->file('thumbnail');
        $thumbnailPath = $this->storePublicBlogThumbnail($file);
        if ($thumbnailPath === null) {
            return back()
                ->withErrors(['thumbnail' => 'Gagal menyimpan gambar (file sementara tidak terbaca). Coba unggah ulang atau periksa upload_tmp_dir / post_max_size di PHP.'])
                ->withInput();
        }
        $slug = Str::slug($request->title);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $status = ($request->action === 'draft') ? 'draft' : 'published';

        Article::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'category' => $request->category,
            'author_id' => Auth::id(),
            'status' => $status,
        ]);

        $message = ($status === 'draft') ? 'Draft artikel berhasil disimpan!' : 'Artikel baru berhasil diterbitkan!';
        return redirect()->route('admin.blog')->with('success', $message);
    }

    public function edit(Article $article)
    {
        return view('blog.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $text = trim(preg_replace('/\s|&nbsp;/u', '', strip_tags((string) $value)));
                    if ($text === '') {
                        $fail('Isi artikel tidak boleh kosong.');
                    }
                },
            ],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'required|string|max:255',
        ], [
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5 MB. Kompres gambar atau pilih file yang lebih kecil.',
        ]);

        $thumbnailPath = $article->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $upload = $request->file('thumbnail');
            $stored = $this->storePublicBlogThumbnail($upload);
            if ($stored === null) {
                return back()
                    ->withErrors(['thumbnail' => 'Gagal menyimpan gambar baru. Coba unggah ulang.'])
                    ->withInput();
            }
            if (filled($article->thumbnail) && Storage::disk('public')->exists($article->thumbnail)) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $thumbnailPath = $stored;
        }

        $slug = Str::slug($request->title);
        if ($slug !== $article->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $status = ($request->action === 'draft') ? 'draft' : 'published';

        $article->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => $slug,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'category' => $request->category,
            'status' => $status,
        ]);

        $message = ($status === 'draft') ? 'Draft artikel berhasil diperbarui!' : 'Artikel berhasil diperbarui dan diterbitkan!';
        return redirect()->route('admin.blog')->with('success', $message);
    }

    public function destroy(Article $article)
    {
        if (filled($article->thumbnail) && Storage::disk('public')->exists($article->thumbnail)) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        $article->delete();

        return redirect()->route('admin.blog')->with('success', 'Artikel berhasil dihapus!');
    }

    public function showPublic($slug)
    {
        $article = Article::where('slug', $slug)->where('status', 'published')->firstOrFail();
        
        $relatedArticles = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->latest()
            ->take(2)
            ->get();
            
        return view('blog.show', compact('article', 'relatedArticles'));
    }

    private function storePublicBlogThumbnail(UploadedFile $file): ?string
    {
        if (! $file->isValid()) {
            return null;
        }

        $pathname = $file->getPathname();
        $contents = @file_get_contents($pathname);
        if ($contents === false) {
            return null;
        }

        $relativePath = 'blog_thumbnails/'.$file->hashName();
        Storage::disk('public')->put($relativePath, $contents);

        return $relativePath;
    }
}
