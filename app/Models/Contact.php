<?php

namespace App\Models;

use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Events\ContactPersistedFromRouteBinding;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberFormat;
use Homeful\Common\Traits\HasMeta;
use Spatie\ModelStates\HasStates;
use Illuminate\Support\Carbon;
use App\States\ContactState;

/**
 * Class Contact
 *
 * @property string $id
 * @property string $mobile
 * @property string $mobile_country
 * @property string $email
 * @property ContactState $state
 * @property Organization $organization
 * @property Campaign $campaign
 * @property SchemalessAttributes $meta
 * @property string $name
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property Carbon $availed_at
 * @property bool $availed
 *
 * @method int getKey()
 */
class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    use Notifiable;
    use HasStates;
    use HasUuids;
    use HasMeta;

    const PHONE_SEARCH_FORMAT = PhoneNumberFormat::E164;
    protected $fillable = [
        'mobile', 'name', 'first_name', 'middle_name', 'last_name','email'
    ];

    protected $casts = [
        'state' => ContactState::class
    ];

    public static function fromMobile(string $value): ?static
    {
        $contact = null;
        try {
            $mobile = phone($value, 'PH', self::PHONE_SEARCH_FORMAT);
            $contact = static::where('mobile', $mobile)->first();
        }
        catch (NumberParseException $exception) {

        }

        return $contact;
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $contact = null;
        try {
            $mobile = phone($value, 'PH', self::PHONE_SEARCH_FORMAT);
            $contact = static::where('mobile', $mobile)->firstOrCreate(['mobile' => $mobile]);
            ContactPersistedFromRouteBinding::dispatchIf($contact->wasRecentlyCreated);
        }
        catch (NumberParseException $exception) {

        }

        return $contact;
    }

    public function routeNotificationForEngageSpark(): string
    {
        return $this->mobile;
    }

    public function organization(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function checkins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function lastest_checkin(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->checkins()->latest();
    }

    public function trips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Trips::class,'contact_id','id');
    }

    public function latest_trip(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->trips()->latest();
    }

    public function smsLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SmsLogs::class,'contacts_id');
    }

    public function getMobileCountryAttribute(): string
    {
        return 'PH';
    }

//    protected function Mobile(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => phone($value, $this->mobile_country)->formatForMobileDialingInCountry($this->mobile_country),
//            set: fn ($value) => phone($value, $this->mobile_country, self::PHONE_SEARCH_FORMAT),
//        );
//    }

    public function getNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get('name')?? $this->first_name.' '.$this->last_name;
    }

    public function setFirstNameAttribute(string $value): static
    {
        $this->getAttribute('meta')->set('first_name', $value);

        return $this;
    }

    public function getFirstNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get('first_name');
    }

    public function setMiddleNameAttribute(string $value): static
    {
        $this->getAttribute('meta')->set('middle_name', $value);

        return $this;
    }

    public function getMiddleNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get('middle_name');
    }

    public function setLastNameAttribute(string $value): static
    {
        $this->getAttribute('meta')->set('last_name', $value);

        return $this;
    }

    public function getLastNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get('last_name');
    }

    public function setAvailedAttribute(bool $value): self
    {
        $this->setAttribute('availed_at', $value ? now() : null);

        return $this;
    }

    public function getAvailedAttribute(): bool
    {
        return $this->getAttribute('availed_at')
            && $this->getAttribute('availed_at') <= now();
    }
}
