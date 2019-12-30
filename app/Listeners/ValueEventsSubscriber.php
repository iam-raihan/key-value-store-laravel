<?php

namespace App\Listeners;

use App\Jobs\UpdateCacheJob;
use App\Jobs\UpdateTTLJob;
use App\Jobs\DeleteExpiredValuesJob;

class ValueEventsSubscriber
{
    public function onValuesReadOperation($event) {
        UpdateCacheJob::dispatchNow($event->getValues()); // dispatches synchronously

        UpdateTTLJob::dispatch(
            $event->getKeys(),
            $event->getExpiresAt()
        ); // time consuming query added to queue to improve performance

        $this->dispatchDeleteExpiredValuesJob($event);

    }

    public function onValuesWriteOperation($event) {
        UpdateCacheJob::dispatchNow($event->getValues());

        $this->dispatchDeleteExpiredValuesJob($event);
    }

    private function dispatchDeleteExpiredValuesJob($event)
    {
        $ttl = config('app.ttl');
        DeleteExpiredValuesJob::dispatch($event->getKeys())
            ->delay(now()->addSeconds($ttl * 60 + 3));
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
