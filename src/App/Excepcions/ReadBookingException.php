<?php

namespace App\Excepcions;

class ReadBookingException extends \Exception
{
    public function __construct(string $message = "No se ha encontrado la reserva en la base de datos", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}