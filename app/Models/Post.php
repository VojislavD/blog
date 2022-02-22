<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
