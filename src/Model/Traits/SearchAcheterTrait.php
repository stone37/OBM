<?php

namespace App\Model\Traits;

trait SearchAcheterTrait
{
    /**
     * @var string|null
     */
    private $state;

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }
}


