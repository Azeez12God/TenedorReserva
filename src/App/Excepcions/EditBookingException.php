<?php

namespace App\Excepcions;

class EditBookingException extends \Exception
{
    public function __construct(string $message = "Error al editar la reserva", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}