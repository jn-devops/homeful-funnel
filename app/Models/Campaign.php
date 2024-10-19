<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Campaign
 *
 * @property string $id
 * @property string $name
 * @property SchemalessAttributes $meta
 *
 * @method int getKey()
 */
class Campaign extends Model
{
    /** @use HasFactory<\Database\Factories\CampaignFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'name'
    ];

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }
}
