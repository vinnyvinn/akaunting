<?php

namespace Modules\Crm\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\Crm\Models\DealActivityType;
use Modules\Crm\Models\DealPipeline;
use Modules\Crm\Models\DealPipelineStage;

class Pipelines extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    public function create()
    {
        $company_id = $this->command->argument('company');
        $user = user();

        // Deal Activity Types Example data
        $deal_activiy_types = [
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'name' => trans('crm::general.activity_types.call'),
                'rank' => 1,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'name' => trans('crm::general.activity_types.meeting'),
                'rank' => 2,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'name' => trans_choice('crm::general.tasks', 1),
                'rank' => 3,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'name' => trans('crm::general.activity_types.dead_line'),
                'rank' => 4,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'name' => trans('general.email'),
                'rank' => 5,
            ]
        ];

        foreach ($deal_activiy_types as $deal_activiy_type) {
            DealActivityType::create($deal_activiy_type);
        }

        // Deal Pipeline Example data
        $deal_pipeline = DealPipeline::create([
            'company_id' => $company_id,
            'created_by' => $user ? $user->id: 0,
            'name' => trans('crm::general.pipeline'),
        ]);

        // Deal Pipeline Stages Example data
        $deal_piplene_stages = [
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'pipeline_id' => $deal_pipeline->id,
                'name' => trans('crm::general.pipeline_stages.proposal_made'),
                'life_stage' => 'not_change',
                'rank' => 1,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'pipeline_id' => $deal_pipeline->id,
                'name' => trans('crm::general.pipeline_stages.lead_in'),
                'life_stage' => 'not_change',
                'rank' => 2,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'pipeline_id' => $deal_pipeline->id,
                'name' => trans('crm::general.pipeline_stages.contact_made'),
                'life_stage' => 'not_change',
                'rank' => 3,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'pipeline_id' => $deal_pipeline->id,
                'name' => trans('crm::general.pipeline_stages.demo_scheduled'),
                'life_stage' => 'not_change',
                'rank' => 4,
            ],
            [
                'company_id' => $company_id,
                'created_by' => $user ? $user->id: 0,
                'pipeline_id' => $deal_pipeline->id,
                'name' => trans('crm::general.pipeline_stages.negotitions_started'),
                'life_stage' => 'not_change',
                'rank' => 5,
            ]
        ];

        foreach ($deal_piplene_stages as $deal_piplene_stage) {
            DealPipelineStage::create($deal_piplene_stage);
        }
    }
}
