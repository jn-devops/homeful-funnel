<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SocialMediaCampaign extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;


    protected $table = 'social_media_campaigns';

    protected $fillable = [
        'name',
        'project_code',
        'date_from',
        'date_to',
        'author_id',
        'social_media_code',
        'registration_logo',
        'registration_background',
        'redirect_url',
        'chat_url',
        'sms_feedback',
        'splash_image_url',
        'trip_label',
        'undecided_label',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $uuid = (string) \Illuminate\Support\Str::uuid();
            } while (self::where($model->getKeyName(), $uuid)->exists());

            $model->{$model->getKeyName()} = $uuid;
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
