<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Checkin
 *
 * @property string $id
 * @property Contact $contact
 * @property Campaign $campaign
 * @property SchemalessAttributes $meta
 * @property string registration_code
 * @property Project $project
 *
 * @method int getKey()
 */
class Checkin extends Model
{
    /** @use HasFactory<\Database\Factories\CheckinFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    const REG_CODE_UUID_GROUP_INDEX = 5;
    const REG_CODE_SUBSTRING_COUNT = 6;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'campaign_id',
        'contact_id',
        'rider_url',
        'project_id',

    ];

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
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

    public function setRiderUrlAttribute(string $value): static
    {
        $this->meta->set('rider_url', $value);
        return $this;
    }

    public function getRiderUrlAttribute(): ?string
    {
        return $this->meta->get('rider_url');
    }
}
