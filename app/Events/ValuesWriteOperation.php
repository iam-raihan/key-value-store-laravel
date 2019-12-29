<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ValuesWriteOperation
{
    use Dispatchable, SerializesModels;

    protected $values;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
}
