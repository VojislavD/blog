<?php

namespace App\Models;

use App\Enums\CommentStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'body',
        'featured_image',
        'published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereStatus(CommentStatus::Approved);
    }

    public function scopePublished(): Builder
    {
        return $this->where('published', true);
    }

    public function author(): Attribute
    {
        return new Attribute(
            get: fn() => $this->user->name
        );
    }

    public function publishedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => \Carbon\Carbon::parse($value)->toFormattedDateString()
        );
    }

    public function displayFeaturedImage()
    {
        return $this->featured_image 
            ? Storage::url($this->featured_image) 
            : ''; 
    }
}
