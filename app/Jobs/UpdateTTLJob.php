<?php

namespace App\Jobs;

use App\Models\Value;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTTLJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $keys;
    protected $expiresAt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $keys, $expiresAt)
    {
        $this->keys = $keys;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Value::whereIn('key', $this->keys)
            ->orderBy('key')
            ->update(['expires_at' => $this->expiresAt]);
    }
}
