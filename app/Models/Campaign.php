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
 * @property string $rider_url
 * @property string $feedback
 * @property string $avail_label
 * @property string $trip_label
 * @property string $undecided_label
 * @property string $event_date
 * @property string $event_date_to
 * @property string $event_date_from
 * @property string $event_date_to
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
        'splash_image_url',
        'event_date',
        'event_date_to',
        'event_time_from',
        'event_time_to',
        'rider_url',
        'feedback',
        'avail_label',
        'trip_label',
        'undecided_label'
    ];

    protected $appends = [
        'rider_url',
        'feedback',
    ];

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function projectCampaigns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProjectCampaign::class);
    }

    public function projects()
    {
        return $this->hasManyThrough(Project::class, ProjectCampaign::class, 'campaign_id', 'id', 'id', 'project_id');
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function organizations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
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

    public function setSplashImageUrlAttribute(string $value): static
    {
        $this->meta->set('splash_image_url', $value);
        return $this;
    }

    public function getSplashImageUrlAttribute(): ?string
    {
        return $this->meta->get('splash_image_url');
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

    public function setEventDateToAttribute(string $value): static
    {
        $this->meta->set('event_date_to', $value);
        return $this;
    }

    public function getEventDateToAttribute(): ?string
    {
        return $this->meta->get('event_date_to');
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

    public function setRiderUrlAttribute(string $value): static
    {
        $this->meta->set('rider_url', $value);
        return $this;
    }

    public function getRiderUrlAttribute(): ?string
    {
        return $this->meta->get('rider_url');
    }

    public function setFeedbackAttribute(string $value): static
    {
        $this->meta->set('feedback', $value);
        return $this;
    }

    public function getFeedbackAttribute(): ?string
    {
        return $this->meta->get('feedback');
    }

    public function setAvailLabelAttribute(string $value): static
    {
        $this->meta->set('avail_label', $value);
        return $this;
    }

    public function getAvailLabelAttribute(): ?string
    {
        return $this->meta->get('avail_label', 'Avail Now');
    }

    public function setTripLabelAttribute(string $value): static
    {
        $this->meta->set('trip_label', $value);
        return $this;
    }

    public function getTripLabelAttribute(): ?string
    {
        return $this->meta->get('trip_label', 'Schedule Visit');
    }

    public function setUndecidedLabelAttribute(string $value): static
    {
        $this->meta->set('undecided_label', $value);
        return $this;
    }

    public function getUndecidedLabelAttribute(): ?string
    {
        return $this->meta->get('undecided_label', 'Not Now');
    }
}
