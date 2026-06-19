<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'role_label', 'parent_type', 'gender', 'parenting_role', 'bio', 'avatar_color', 'avatar_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** The generic option, shown alongside the audiences in "Ik ben…". */
    public const GENERIC_PARENT_TYPE = ['ouder' => 'Ouder'];

    /** Gender options (self-identification, always optional). */
    public const GENDERS = [
        'vrouw' => 'Vrouw',
        'man' => 'Man',
        'anders' => 'Anders',
        'geen' => 'Zeg ik liever niet',
    ];

    /** Parenting role — distinguishes mothers, fathers, co-parents, etc. */
    public const PARENTING_ROLES = [
        'moeder' => 'Moeder',
        'vader' => 'Vader',
        'meeouder' => 'Meeouder',
        'aanstaande_ouder' => 'Aanstaande ouder',
        'verzorger' => 'Verzorger of voogd',
        'anders' => 'Anders',
    ];

    /** Roles for which we surface the father-focused content & checklist. */
    public const FATHER_ROLES = ['vader', 'aanstaande_ouder'];

    /** Per-request memoised key => label map. */
    protected static ?array $parentTypeMap = null;

    /**
     * "Ik ben…" options: the generic "Ouder" plus every audience (by slug).
     *
     * @return array<string, string>
     */
    public static function parentTypeMap(): array
    {
        if (static::$parentTypeMap !== null) {
            return static::$parentTypeMap;
        }

        $map = self::GENERIC_PARENT_TYPE;

        try {
            if (Schema::hasTable('audiences')) {
                $map = array_merge($map, Audience::orderBy('position')->pluck('name', 'slug')->all());
            }
        } catch (\Throwable $e) {
            // table not ready yet (e.g. during migrate) — fall back to the generic option
        }

        return static::$parentTypeMap = $map;
    }

    /** Append computed values to serialized output (incl. shared Inertia auth.user). */
    protected $appends = ['parent_type_label', 'gender_label', 'parenting_role_label', 'is_father', 'avatar_url'];

    protected function parentTypeLabel(): Attribute
    {
        return Attribute::get(fn () => self::parentTypeMap()[$this->parent_type] ?? 'Ouder');
    }

    protected function genderLabel(): Attribute
    {
        return Attribute::get(fn () => self::GENDERS[$this->gender] ?? null);
    }

    protected function parentingRoleLabel(): Attribute
    {
        return Attribute::get(fn () => self::PARENTING_ROLES[$this->parenting_role] ?? null);
    }

    /** Whether the father-focused content should be surfaced for this user. */
    protected function isFather(): Attribute
    {
        return Attribute::get(fn () => in_array($this->parenting_role, self::FATHER_ROLES, true));
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->avatar_path) {
                return null;
            }
            try {
                return Storage::disk('r2')->url($this->avatar_path);
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function favouriteNames(): BelongsToMany
    {
        return $this->belongsToMany(BabyName::class);
    }

    public function checkedItems(): BelongsToMany
    {
        return $this->belongsToMany(ChecklistItem::class);
    }

    public function followedGroups(): BelongsToMany
    {
        return $this->belongsToMany(CommunityGroup::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_user_like');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
