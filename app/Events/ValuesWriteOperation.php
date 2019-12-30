<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ValuesWriteOperation
{
    use Dispatchable;

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

    public function getValues()
    {
        return $this->values;
    }

    public function getKeys()
    {
        return array_column($this->values, 'key');
    }
}
