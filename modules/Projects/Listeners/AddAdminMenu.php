<?php
namespace Modules\Projects\Listeners;

use App\Events\Menu\AdminCreated as Event;

class AddAdminMenu
{

    /**
     * Handle the event.
     *
     * @param AdminMenuCreated $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = auth()->user();

        if (! $user->can('read-projects')) {
            return;
        }

        // Add Project Main Heading
        $event->menu->add([
            'url' => 'projects/projects',
            'title' => trans('projects::general.title'),
            'icon' => 'fa fa-flask',
            'order' => 2
        ]);
    }
}
