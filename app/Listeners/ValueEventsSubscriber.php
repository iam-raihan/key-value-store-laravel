<?php

namespace App\Listeners;

class ValueEventsSubscriber
{
    public function onValueReadOperation($event) {

    }

    public function onValueWriteOperation($event) {

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ValuesReadOperation',
            'App\Listeners\ValueEventsSubscriber@onValueReadOperation'
        );

        $events->listen(
            'App\Events\ValuesWriteOperation',
            'App\Listeners\ValueEventsSubscriber@onValueWriteOperation'
        );
    }
}
