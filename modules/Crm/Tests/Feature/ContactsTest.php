<?php

namespace Modules\Crm\Tests\Feature;

use Modules\Crm\Models\Contact;
use Modules\Crm\Jobs\CreateContact;
use Tests\Feature\FeatureTestCase;

class ContactsTest extends FeatureTestCase
{
    public function testItShouldSeeContactListPage()
    {
        $this->loginAs()
            ->get(route('crm.contacts.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('crm::general.contacts', 2));
    }

    public function testItShouldSeeContactCreatePage()
    {
        $this->loginAs()
            ->get(route('crm.contacts.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('crm::general.contacts', 1)]));
    }

    public function testItShouldCreateContact()
    {
        $this->loginAs()
            ->post(route('crm.contacts.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeContactUpdatePage()
    {
        $contact = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
            ->get(route('crm.contacts.edit', $contact->id))
            ->assertStatus(200)
            ->assertSee($contact->mobile);
    }

    public function testItShouldUpdateContact()
    {
        $request = $this->getRequest();

        $contact = $this->dispatch(new CreateContact($request));

        $request['name'] = $this->faker->name;

        $this->loginAs()
             ->patch(route('crm.contacts.update', $contact->id), $request)
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteContact()
    {
        $contact = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
             ->delete(route('crm.contacts.destroy', $contact->id))
             ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeContactDetailPage()
    {
        $contact = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
            ->get(route('crm.contacts.show', $contact->id))
            ->assertStatus(200)
            ->assertSee($contact->name);
    }

    public function getRequest()
    {
        return factory(Contact::class)->raw();
    }
}
