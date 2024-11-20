<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactStateHistory extends Model
{
    protected $fillable = [
        'contact_id',
        'state',
        'remarks',
    ];

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
