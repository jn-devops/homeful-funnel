<?php

namespace App\Models;

use App\States\TrippingState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Trips extends Model
{
    use HasStates;
    protected $fillable = [
        'campaign_id',
        'contact_id',
        'project_id',
        'checkin_id',
        'preferred_date',
        'preferred_time',
        'remarks',
        'assigned_to',
        'assigned_to_mobile',
        'completed_ts'
    ];

    protected $casts = [
        'preferred_date' => 'date',
        // 'preferred_time' => 'time',
        'state' => TrippingState::class
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
