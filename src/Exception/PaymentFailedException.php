<?php

namespace App\Exception;

use Exception;

class PaymentFailedException extends Exception
{
    public static function fromHttpException(Exception $exception): self
    {
        return new self($exception->getMessage());
    }
}
