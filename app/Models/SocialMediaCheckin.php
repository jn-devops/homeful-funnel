<?php

namespace App\Models;

use App\Actions\GenerateAvailUrl;
use Homeful\Common\Traits\HasMeta;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaCheckin extends Model
{
    use HasFactory;
    use HasUuids;
    use HasMeta;

    const REG_CODE_UUID_GROUP_INDEX = 5;
    const REG_CODE_SUBSTRING_COUNT = 6;
    
    protected $fillable = [
        'social_media_campaign_id',
        'contact_id',
        'rider_url',
        'project_id',
        'reference_code'
    ];

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SocialMediaCampaign::class, 'social_media_campaign_id');
    }

    public function getRegistrationCodeAttribute(): string
    {
        $campaign_codes = [];
        $code = $this->id;
        preg_match('/(.*)-(.*)-(.*)-(.*)-(.*)/', $code, $campaign_codes);

        return substr($campaign_codes[self::REG_CODE_UUID_GROUP_INDEX], self::REG_CODE_SUBSTRING_COUNT);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function trip(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Trips::class);
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

    public function link(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Link::class, 'checkin_id', 'id');
    }

    public function getOrGenerateAvailUrl(): string
    {
        return null == $this->link
            ? app(GenerateAvailUrl::class)->run($this)
            : route('link.show', ['shortUrl' => $this->link->short_url])
            ;
    }
}
