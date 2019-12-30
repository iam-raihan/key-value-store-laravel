<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class UpdateCacheJob
{
    use Dispatchable;

    protected $values;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->values))
            return;

        $ttl = config('app.ttl', 5);

        $values = $this->values;
        Redis::pipeline(function ($pipe) use ($values, $ttl) {
            foreach($values as $value) {
                $value = (array) $value;
                $pipe->set("key:$value[key]", $value['value'], $ttl * 60);
            }
        });
    }
}
