<?php

namespace Modules\Crm\Tests\Feature;

use Modules\Crm\Models\Schedule;
use Tests\Feature\FeatureTestCase;


class SchedulesTest extends FeatureTestCase
{

    public function testItShouldSeeSchedulesListPage()
    {
        $this->loginAs()
             ->get(route('crm.schedules.index'))
             ->assertStatus(200)
             ->assertSeeText(trans_choice('crm::general.schedule',2));
    }
}
