<?php

namespace Modules\Crm\Models;

use App\Abstracts\Model;

class DealPipeline extends Model
{
    protected $table = 'crm_deal_pipelines';

    protected $fillable = [
        'company_id',
        'created_by',
        'name',
    ];

    public function createdBy()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id');
    }

    public function stages()
    {
        return $this->hasMany('Modules\Crm\Models\DealPipelineStage', 'pipeline_id', 'id');
    }

    public function deals()
    {

        return $this->hasMany('Modules\Crm\Models\Deal', 'pipeline_id', 'id');
    }
}
