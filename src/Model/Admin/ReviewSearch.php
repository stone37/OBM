<?php

namespace App\Model\Admin;

class ReviewSearch
{
    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @return bool
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}

