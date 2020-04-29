<?php

namespace Modules\Crm\Tests\Feature;

use Modules\Crm\Models\Deal;
use Modules\Crm\Jobs\CreateDeal;
use Tests\Feature\FeatureTestCase;

class DealsTest extends FeatureTestCase
{
    public function testItShouldSeeDealListPage()
    {
        $this->loginAs()
             ->get(route('crm.deals.index'))
             ->assertStatus(200)
             ->assertSeeText(trans_choice('crm::general.deals', 2));
    }

    public function testItShouldSeeDealCreatePage()
	{
		$this->loginAs()
			->get(route('crm.deals.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('crm::general.deals', 1)]));
	}

    public function testItShouldCreateDeal()
    {
        $this->loginAs()
            ->post(route('crm.deals.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

	public function testItShouldSeeDealUpdatePage()
	{
        $deal = $this->dispatch(new CreateDeal($this->getRequest()));

		$this->loginAs()
			->get(route('crm.deals.edit', $deal->id))
			->assertStatus(200)
			->assertSee($deal->name);
    }

    public function testItShouldUpdateDeal()
    {
        $request = $this->getRequest();

        $deal = $this->dispatch(new CreateDeal($request));

        $request['name'] = $this->faker->name;

        $this->loginAs()
             ->patch(route('crm.deals.update', $deal->id), $request)
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteDeal()
    {
        $deal = $this->dispatch(new CreateDeal($this->getRequest()));

        $this->loginAs()
             ->delete(route('crm.deals.destroy', $deal->id))
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return factory(Deal::class)->raw();
    }
}
