<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealPipelineStage extends Model
{
    protected $table = 'crm_deal_pipeline_stages';

    protected $fillable = [
        'company_id',
        'created_by',
        'pipeline_id',
        'name',
        'life_stage',
        'rank',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function pipelines()
    {
        return $this->hasOne('Modules\Crm\Models\Pipeline', 'id', 'pipeline_id');
    }

    public function deals()
    {
        return $this->hasMany('Modules\Crm\Models\Deal', 'stage_id', 'id');
    }

    public function filterDeals()
    {
        $status = request('status', 'open');

        return $this->deals()->where('status', $status);
    }
}
