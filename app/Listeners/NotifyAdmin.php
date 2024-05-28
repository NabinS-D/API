<?php

namespace App\Listeners;

use App\Events\ProjectAddedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Notifications\ProjectAdd;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class NotifyAdmin
{
    /**
     * Handle the event.
     */
    public function handle(ProjectAddedEvent $event): void
    {
        \Log::info('Handling ProjectAddedEvent for project: ', ['project' => $event->project]);
       
        Notification::send($event->user, new ProjectAdd($event->project,$event->user));    
    }
}
