<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SmsLogs extends Model
{
    protected $fillable = [
        'contacts_id',
        'message',
        'sent_to_mobile',
        'sent_to_email'
    ];

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contacts_id');
    }
}
