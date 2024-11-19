<?php

namespace App\Models;

use App\States\TrippingState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
        'completed_ts',
        'last_updated_by',
        'assigned_date',
        'confirmed_date',
        'cancelled_from_state',
        'cancelled_date',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'state' => TrippingState::class,
        'cancelled_from_state' => TrippingState::class,
    ];
    public static function booted(): void
    {
        static::updating(function ($data) {
            foreach (array_keys($data->getAttributes()) as $attr) {
                if ($data->isDirty($attr)) {
                    $from = $data->getOriginal($attr);
                    $to = $data->getAttribute($attr);

                    if (Str::endsWith($attr, '_id') && method_exists($data, Str::camel(str_replace('_id', '', $attr)))) {
                        $relationshipName = Str::camel(str_replace('_id', '', $attr));
                        $relatedModel = $data->$relationshipName()->getRelated();

                        // Retrieve the name or another identifier instead of the ID
                        $from = $data->getOriginal($attr) ? optional($relatedModel->find($data->getOriginal($attr)))->name : null;
                        $to = $data->getAttribute($attr) ? optional($relatedModel->find($data->getAttribute($attr)))->name : null;
                    }

                    // Convert SchemalessAttributes instances to JSON if needed
                    if ($from instanceof \Spatie\SchemalessAttributes\SchemalessAttributes) {
                        $from = json_encode($from->all());
                    }
                    if ($to instanceof \Spatie\SchemalessAttributes\SchemalessAttributes) {
                        $to = json_encode($to->all());
                    }

                    $data->updateLog()->create([
                        'field' => Str::endsWith($attr, '_id') ? str_replace('_id', '', $attr) : $attr,
                        'from' => $from ?? '',
                        'to' => $to ?? '',
                        'user_id' => \auth()->user()->id ?? 0
                    ]);

                }
            }
        });
    }


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
    public function updateLog()
    {
        return $this->morphMany(UpdateLog::class, 'loggable');
    }
}
