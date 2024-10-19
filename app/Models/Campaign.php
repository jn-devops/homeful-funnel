<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Campaign
 *
 * @property string $id
 * @property string $name
 *
 * @method int getKey()
 */
class Campaign extends Model
{
    /** @use HasFactory<\Database\Factories\CampaignFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name'
    ];

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }
}
