<?php

namespace App\Listeners;

use App\Jobs\UpdateCacheJob;
use App\Jobs\UpdateTTLJob;

class ValueEventsSubscriber
{
    public function onValuesReadOperation($event) {
        UpdateCacheJob::dispatchNow($event->getValues());

        UpdateTTLJob::dispatch(
            $event->getKeys(),
            $event->getExpiresAt()
        ); // time consuming query queued to improve performance
    }

    public function onValuesWriteOperation($event) {
        UpdateCacheJob::dispatchNow($event->getValues());
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
            'App\Listeners\ValueEventsSubscriber@onValuesReadOperation'
        );

        $events->listen(
            'App\Events\ValuesWriteOperation',
            'App\Listeners\ValueEventsSubscriber@onValuesWriteOperation'
        );
    }
}
