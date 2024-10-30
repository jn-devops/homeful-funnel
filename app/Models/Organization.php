<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Organization
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property SchemalessAttributes $meta
 *
 * @method int getKey()
 */
class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'code', 'name'
    ];

    public static function booted(): void
    {
        static::creating(function (Organization $organization) {
            $uuid = [];
            preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $organization->id, $uuid);
            $organization->code = $uuid[4];
        });
    }

    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function campaigns(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Campaign::class);
    }
}
