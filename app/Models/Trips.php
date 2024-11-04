<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    protected $fillable = [
        'campaign_id',
        'contact_id',
        'project_id',
        'checkin_id',
        'preferred_date',
        'preferred_time',
        'remarks',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        // 'preferred_time' => 'time',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function checkin()
    {
        return $this->belongsTo(Checkin::class);
    }
}
