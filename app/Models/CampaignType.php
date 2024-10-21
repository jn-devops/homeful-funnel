<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;

/**
 * Class CampaignType
 *
 * @property string $id
 * @property string $name
 * @property SchemalessAttributes $meta
 *
 * @method int getKey()
 */
class CampaignType extends Model
{
    /** @use HasFactory<\Database\Factories\CampaignTypeFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'name'
    ];
}
