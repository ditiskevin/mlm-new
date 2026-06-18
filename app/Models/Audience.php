<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audience extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tips' => 'array',
        'position' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function group(): HasOne
    {
        return $this->hasOne(CommunityGroup::class);
    }

    public function forumCategory(): HasOne
    {
        return $this->hasOne(ForumCategory::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return array<int, string>
     */
    public function paragraphs(): array
    {
        return preg_split('/\n\s*\n/', trim($this->body)) ?: [];
    }
}
