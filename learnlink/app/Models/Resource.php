<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Resource extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    public const CATEGORIES = [
        'notes'       => 'Notes',
        'slides'      => 'Slides',
        'past-papers' => 'Past Papers',
        'assignments' => 'Assignments',
        'books'       => 'Books',
        'other'       => 'Other',
    ];

    protected $fillable = [
        'user_id',
        'course_class_id',
        'title',
        'slug',
        'description',
        'category',
        'file_path',
        'original_name',
        'mime_type',
        'file_type',
        'size_bytes',
        'download_count',
        'downloads_count',
        'views_count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes'     => 'integer',
            'download_count' => 'integer',
            'downloads_count' => 'integer',
            'views_count'    => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Routing
    // -------------------------------------------------------------------------

    /** Pretty URLs: /resources/{slug} rather than /resources/{id}. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function courseClass(): BelongsTo { return $this->belongsTo(CourseClass::class); }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // -------------------------------------------------------------------------
    // Attributes
    // -------------------------------------------------------------------------

    /** Wire score derived from the vote table, e.g. +5 or -2. */
    public function score(): int
    {
        $up = $this->votes()->where('value', 1)->count();
        $down = $this->votes()->where('value', -1)->count();

        return $up - $down;
    }

    /** Human friendly file size, e.g. "1.2 MB". */
    public function humanSize(): string
    {
        $bytes = (int) $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $bytes < 10 && $i > 0 ? 1 : 0).' '.$units[$i];
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    // -------------------------------------------------------------------------
    // Lifecycle
    // -------------------------------------------------------------------------

    protected static function booted(): void
    {
        static::creating(function (Resource $resource) {
            if (empty($resource->slug)) {
                $resource->slug = static::uniqueSlug($resource->title);
            }
        });
    }

    protected static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'resource';
        $slug = $base;
        $counter = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
