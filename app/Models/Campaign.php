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
 * @property Project $project
 * @property CampaignType $campaignType
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
        'name',
        'splash_image',
        'event_date',
        'event_time_from',
        'event_time_to',
    ];

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function campaignType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CampaignType::class);
    }

    public function setSplashImageAttribute(string $value): static
    {
        $this->meta->set('splash_image', $value);
        return $this;
    }

    public function getSplashImageAttribute(): ?string
    {
        return $this->meta->get('splash_image');
    }

    public function setEventDateAttribute(string $value): static
    {
        $this->meta->set('event_date', $value);
        return $this;
    }

    public function getEventDateAttribute(): ?string
    {
        return $this->meta->get('event_date');
    }

    public function setEventTimeFromAttribute(string $value): static
    {
        $this->meta->set('event_time_from', $value);
        return $this;
    }

    public function getEventTimeFromAttribute(): ?string
    {
        return $this->meta->get('event_time_from');
    }

    public function setEventTimeToAttribute(string $value): static
    {
        $this->meta->set('event_time_to', $value);
        return $this;
    }

    public function getEventTimeToAttribute(): ?string
    {
        return $this->meta->get('event_time_to');
    }
}
