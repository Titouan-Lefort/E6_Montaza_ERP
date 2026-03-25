<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
        'media_type_id',
        'commentaire_id',
    ];

    public const AUTHORIZED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'video/mp4',
        'audio/mpeg',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'text/csv',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'image/jpg',
        'audio/mp3',
        'image/heic',
        'image/heif',
        'video/mov',
        'video/avi',
        'video/wmv',
    ];

    public const AUTHORIZED_FILE_EXTENSIONS = [
        '.jpeg',
        '.png',
        '.gif',
        '.mp4',
        '.mpeg',
        '.pdf',
        '.doc',
        '.docx',
        '.xls',
        '.csv',
        '.xlsx',
        '.txt',
        '.jpg',
        '.mp3',
        '.heic',
        '.heif',
        '.mov',
        '.avi',
        '.wmv',
    ];

    /**
     * Get the parent mediaable model.
     */
    public function mediaable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who uploaded the media.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full path to the media file.
     */
    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->path);
    }

    /**
     * Get the public URL to the media file.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function mediaType(): BelongsTo
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class, 'commentaire_id');
    }
}
