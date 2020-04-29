<?php

namespace Modules\Crm\Http\Controllers;

use Modules\Crm\Models\DealActivityType;
use Modules\Crm\Http\Requests\SettingRequest as Request;
use App\Abstracts\Http\Controller;
use Modules\Crm\Models\DealPipeline;
use Modules\Crm\Models\DealPipelineStage;

class Settings extends Controller
{

    public function edit()
    {
        $activities = DealActivityType::orderBy('rank', 'asc')->get();

        $pipeline = DealPipeline::all();

        return view('crm::settings.edit', compact('activities', 'pipeline'));
    }

    public function getActivityType()
    {
        $html = view('crm::modals.setting.activity_type')->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function createActivityType(Request $request)
    {
        DealActivityType::create(array_merge($request->input(), [
            'company_id' => session('company_id'),
            'created_by' => user()->id,
        ]));

        $setting = DealActivityType::all();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
            'html' => $setting,
        ];

        return response()->json($response);
    }

    public function editActivityType(DealActivityType $activity)
    {
        $html = view('crm::modals.setting.activity_type_edit', compact('activity'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function storeActivityType(DealActivityType $activity, Request $request)
    {
        $request['company_id'] = session('company_id');
        $request['created_by'] = user()->id;

        $activity->update($request->input());
        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
        ];

        return response()->json($response);
    }

    public function destroyActivityType(DealActivityType $activity)
    {
        $activity->delete();
        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
            'message' => 'null',
            'html' => null,
        ];

        return response()->json($response);

    }

    public function getPipeline(DealPipeline $pipeline)
    {
        $life_stage = [
            'customer' => trans('crm::general.stage.customer'),
            'lead' => trans('crm::general.stage.lead'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber'),
            'not_change' => trans('crm::general.stage.not_change')
        ];

        $html = view('crm::modals.setting.pipeline', compact('pipeline', 'life_stage'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function createPipeline(Request $request)
    {
        DealPipeline::create(array_merge($request->input(), [
            'company_id' => session('company_id'),
            'created_by' => user()->id,
        ]));

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
        ];

        return response()->json($response);
    }

    public function getStage(DealPipeline $pipeline)
    {
        $life_stage = [
            'customer' => trans('crm::general.stage.customer'),
            'lead' => trans('crm::general.stage.lead'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber'),
            'not_change' => trans('crm::general.stage.not_change')
        ];


        $html = view('crm::modals.setting.stage', compact('pipeline', 'life_stage'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function createStage(DealPipeline $pipeline, Request $request)
    {
        DealPipelineStage::create(array_merge($request->input(), [
            'company_id' => session('company_id'),
            'created_by' => user()->id,
        ]));

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
        ];

        return response()->json($response);
    }

    public function destroyStage(DealPipelineStage $stage)
    {
        $stage->delete();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
            'message' => 'null',
            'html' => null,
        ];

        return response()->json($response);
    }

    public function destroyPipeline(DealPipeline $pipeline)
    {
        $pipeline->delete();

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
            'message' => 'null',
            'html' => null,
        ];

        return response()->json($response);
    }

    public function editStage(DealPipelineStage $stage)
    {
        $life_stage = [
            'customer' => trans('crm::general.stage.customer'),
            'lead' => trans('crm::general.stage.lead'),
            'opportunity' => trans('crm::general.stage.opportunity'),
            'subscriber' => trans('crm::general.stage.subscriber'),
            'not_change' => trans('crm::general.stage.not_change')
        ];

        $pipeline = $stage->pipeline_id;

        $html = view('crm::modals.setting.stage_edit', compact('stage', 'life_stage', 'pipeline'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => 'null',
            'html' => $html,
        ]);
    }

    public function storeStage(DealPipelineStage $stage, Request $request)
    {
        $stage->update($request->input());

        $response = [
            'success' => true,
            'error' => false,
            'redirect' => url('crm/settings'),
            'data' => null,
        ];

        return response()->json($response);
    }

    public function activityRankUpdate(Request $request)
    {
        foreach ($request->activities as $rank => $activity) {
            DealActivityType::where('id', $activity['id'])->update(['rank' => $rank + 1]);
        }

        $response = [
            'success' => true,
            'errors' => false,
            'title' => '',
            'message' => '',
            'html' => null,
        ];

        if ($response['success']) {
            $response['message'] = trans('crm::general.activity_type_change');
        }

        return response()->json($response);
    }

    public function stageRankUpdate(Request $request)
    {
        foreach ($request->stages as $rank => $stage) {
            DealPipelineStage::where('id', $stage['id'])->update(['rank' => $rank + 1]);
        }

        $response = [
            'success' => true,
            'errors' => false,
            'title' => '',
            'message' => '',
            'html' => null,
        ];

        if ($response['success']) {
            $response['message'] = trans('crm::general.pipeline_stage_change');
        }

        return response()->json($response);
    }
}
