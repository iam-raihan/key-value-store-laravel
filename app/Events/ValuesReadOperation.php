<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ValuesReadOperation
{
    use Dispatchable;

    protected $values;
    protected $expiresAt;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $values, $expiresAt)
    {
        $this->values = $values;
        $this->expiresAt = $expiresAt;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getKeys()
    {
        return array_column($this->values, 'key');
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
}
