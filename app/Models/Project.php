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
 * @property string $rider_url
 * @property string $default_product
 * @property float $minimum_salary
 * @property float $default_price
 * @property float $default_percent_down_payment
 * @property float $default_percent_miscellaneous_fees
 * @property int $default_down_payment_term
 * @property int $default_balance_payment_term
 * @property float $default_balance_payment_interest_rate
 * @property string $default_seller_commission_code
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
        'product_code',
        'rider_url',
        'default_product'
    ];

    protected $appends = [
        'rider_url',
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

    public function setRiderUrlAttribute(string $value): static
    {
        $this->meta->set('rider_url', $value);
        return $this;
    }

    public function getRiderUrlAttribute(): ?string
    {
        return $this->meta->get('rider_url');
    }

    public function setDefaultProductAttribute(string $value): static
    {
        $this->meta->set('default_product', $value);
        return $this;
    }

    public function getDefaultProductAttribute(): ?string
    {
        return $this->meta->get('default_product');
    }

    public function setMinimumSalaryAttribute(float $value): static
    {
        $this->meta->set('minimum_salary', $value);
        return $this;
    }

    public function getMinimumSalaryAttribute(): ?float
    {
        return $this->meta->get('minimum_salary');
    }

    public function setDefaultPriceAttribute(float $value): static
    {
        $this->meta->set('default_price', $value);
        return $this;
    }

    public function getDefaultPriceAttribute(): ?float
    {
        return $this->meta->get('default_price');
    }

    public function setDefaultPercentDownPaymentAttribute(float $value): static
    {
        $this->meta->set('default_percent_down_payment', $value);
        return $this;
    }

    public function getDefaultPercentDownPaymentAttribute(): ?float
    {
        return $this->meta->get('default_percent_down_payment');
    }

    public function setDefaultPercentMiscellaneousFeesAttribute(float $value): static
    {
        $this->meta->set('default_percent_miscellaneous_fees', $value);
        return $this;
    }

    public function getDefaultPercentMiscellaneousFeesAttribute(): ?float
    {
        return $this->meta->get('default_percent_miscellaneous_fees');
    }

    public function setDefaultDownPaymentTermAttribute(int $value): static
    {
        $this->meta->set('default_down_payment_term', $value);
        return $this;
    }

    public function getDefaultDownPaymentTermAttribute(): ?int
    {
        return $this->meta->get('default_down_payment_term');
    }

    public function setDefaultBalancePaymentTermAttribute(int $value): static
    {
        $this->meta->set('default_balance_payment_term', $value);
        return $this;
    }

    public function getDefaultBalancePaymentTermAttribute(): ?int
    {
        return $this->meta->get('default_balance_payment_term');
    }

    public function setDefaultBalancePaymentInterestRateAttribute(float $value): static
    {
        $this->meta->set('default_balance_payment_interest_rate', $value);
        return $this;
    }

    public function getDefaultBalancePaymentInterestRateAttribute(): ?float
    {
        return $this->meta->get('default_balance_payment_interest_rate');
    }

    public function setDefaultSellerCommissionCodeAttribute(string $value): static
    {
        $this->meta->set('default_seller_commission_code', $value);
        return $this;
    }

    public function getDefaultSellerCommissionCodeAttribute(): ?string
    {
        return $this->meta->get('default_seller_commission_code');
    }
}
