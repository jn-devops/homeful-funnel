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
 *
 * @method int getKey()
 */
class Checkin extends Model
{
    /** @use HasFactory<\Database\Factories\CheckinFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
