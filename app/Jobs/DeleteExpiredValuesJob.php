<?php

namespace App\Jobs;

use App\Models\Value;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteExpiredValuesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $keys;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Value::whereIn('key', $this->keys)
                ->where('expires_at', '<=', now())
                ->orderBy('key')
                ->delete();
    }
}
