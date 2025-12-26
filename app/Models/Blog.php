<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'judul', 'slug', 'isi', 'gambar', 'kategori_id', 'penulis_id', 'status', 'tanggal_publish'
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            // Isi penulis otomatis saat create
            if (empty($blog->penulis_id)) {
                $blog->penulis_id = auth()->id();
            }

            // Generate slug jika belum ada
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->judul);
            }
        });

        static::saving(function ($blog) {
            // Atur tanggal_publish otomatis
            if ($blog->status === 'publish' && empty($blog->tanggal_publish)) {
                $blog->tanggal_publish = now();
            }

            if ($blog->status !== 'publish') {
                $blog->tanggal_publish = null;
            }
        });
    }

    // Relasi
    public function kategori()
    {
        return $this->belongsTo(KategoriBlog::class, 'kategori_id');
    }

    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }
}