<?php

namespace Modules\Crm\Tests\Feature;

use Tests\Feature\FeatureTestCase;

class DashboardTest extends FeatureTestCase
{

    public function testItShouldSeeDashboardDealFlow()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.deal_flow'));
    }

    public function testItShouldSeeDashboardTotalDeals()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.total_deals'));
    }

    public function testItShouldSeeDashboardLatestDeals()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.latest_deals'));
    }

    public function testItShouldSeeDashboardTodaySchedule()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.today_schedule'));
    }

    public function testItShouldSeeDashboardTotalContacts()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.total_contacts'));
    }

    public function testItShouldSeeDashboardTotalCompanies()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.total_companies'));
    }

    public function testItShouldSeeDashboardUpcomingSchedule()
	{
        $this->loginAs()
        ->get(route('dashboards.show', 2))
        ->assertStatus(200)
        ->assertSeeText(trans('crm::widgets.upcoming_schedule'));
	}
}
