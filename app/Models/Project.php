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
 * @property string $seller_code
 * @property string $product_code
 *
 * @method int getKey()
 */
class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    use HasUuids;
    use HasMeta;

    protected $fillable = [
        'name',
        'project_image',
        'seller_code',
        'product_code'
    ];
    public function setProjectImageAttribute(string $value): static
    {
        $this->meta->set('project_image', $value);
        return $this;
    }

    public function getProjectImageAttribute(): ?string
    {
        return $this->meta->get('project_image');
    }

    public function setSellerCodeAttribute(string $value): static
    {
        $this->meta->set('seller_code', $value);
        return $this;
    }

    public function getSellerCodeAttribute(): ?string
    {
        return $this->meta->get('seller_code');
    }

    public function setProductCodeAttribute(string $value): static
    {
        $this->meta->set('product_code', $value);
        return $this;
    }

    public function getProductCodeAttribute(): ?string
    {
        return $this->meta->get('product_code');
    }
}
