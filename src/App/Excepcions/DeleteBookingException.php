<?php

namespace App\Excepcions;

class DeleteBookingException extends \Exception
{
    public function __construct(string $message = "Error al borrar la reserva", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}