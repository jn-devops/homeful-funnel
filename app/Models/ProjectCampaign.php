<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCampaign extends Model
{
    protected $fillable = [
        'project_id',
        'campaign_id'
    ];

    protected $appends = [
        'project_name',
        'campaign_name',
    ];

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getProjectNameAttribute(){
        return $this->project->name;
    }
    public function getCampaignNameAttribute(){
        return $this->campaign->name;
    }
}
