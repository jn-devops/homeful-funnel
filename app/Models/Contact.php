<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;
use Spatie\ModelStates\HasStates;
use App\States\ContactState;

/**
 * Class Contact
 *
 * @property string $id
 * @property string $mobile
 * @property ContactState $state
 * @property Organization $organization
 * @property Campaign $campaign
 * @property SchemalessAttributes $meta
 *
 * @method int getKey()
 */
class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    use HasStates;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'mobile'
    ];

    protected $casts = [
        'state' => ContactState::class
    ];

    public function getRouteKeyName(): string
    {
        return 'mobile';
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }
}
