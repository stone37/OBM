<?php

namespace App\Response;

class FileUploaderResponse
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var string|null
     */
    private $error;

    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->success = true;
        $this->error = null;
        $this->data = [];
    }

    public function assemble(): array
    {
        $data = $this->data;
        $data['success'] = $this->success;

        if ($this->success) {
            unset($data['error']);
        }

        if (!$this->success) {
            $data['error'] = $this->error;
        }

        return $data;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setError(string $msg = null): self
    {
        $this->error = $msg;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}
