<?php

namespace Modules\Crm\Tests\Feature;

use Modules\Crm\Models\Company;
use Modules\Crm\Jobs\CreateCompany;
use Tests\Feature\FeatureTestCase;

class CompaniesTest extends FeatureTestCase
{
    public function testItShouldSeeCompanyListPage()
    {
        $this->loginAs()
             ->get(route('crm.companies.index'))
             ->assertStatus(200)
             ->assertSeeText(trans_choice('crm::general.companies', 2));
    }

    public function testItShouldSeeCompanyCreatePage()
	{
		$this->loginAs()
			->get(route('crm.companies.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('crm::general.companies', 1)]));
	}

    public function testItShouldCreateCompany()
    {
        $this->loginAs()
            ->post(route('crm.companies.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

	public function testItShouldSeeCompanyUpdatePage()
	{
        $company = $this->dispatch(new CreateCompany($this->getRequest()));

		$this->loginAs()
			->get(route('crm.companies.edit', $company->id))
			->assertStatus(200)
			->assertSee($company->mobile);
    }

    public function testItShouldUpdateCompany()
    {
        $request = $this->getRequest();

        $company = $this->dispatch(new CreateCompany($request));

        $request['name'] = $this->faker->name;

        $this->loginAs()
             ->patch(route('crm.companies.update', $company->id), $request)
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteCompany()
    {
        $company = $this->dispatch(new CreateCompany($this->getRequest()));

        $this->loginAs()
             ->delete(route('crm.companies.destroy', $company->id))
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeCompanyDetailPage()
	{
		$company = $this->dispatch(new CreateCompany($this->getRequest()));

		$this->loginAs()
			->get(route('crm.companies.show', $company->id))
			->assertStatus(200)
			->assertSee($company->name);
	}

    public function getRequest()
    {
        return factory(Company::class)->raw();
    }
}
