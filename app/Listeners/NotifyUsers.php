<?php

namespace App\Listeners;

use App\Events\TaskAddedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAdd;

class NotifyUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
      //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskAddedEvent $event): void
    {
        \Log::info('Handling for Task added event', ['task'=> $event->task]);

        // Send notification to each user
        Notification::send($event->user, new TaskAdd($event->task, $event->user));
    }
}
