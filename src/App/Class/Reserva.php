<?php

namespace App\Class;

use App\Class\Cliente;
use App\Class\Enum\MetodoPago;
use App\Class\Usuario;
use App\Model\ReservaModel;
use App\Model\UsuarioModel;
use DateTime;
use Ramsey\Uuid\Uuid;

class Reserva
{
    private string $bookinguuid;
    private Usuario $useruuid;
    private DateTime $bookingdate;
    private int $bookingunits;
    private float $bookingcost;
    private Cliente $clientcode;
    private MetodoPago $bookingpaymethod;
    private int $bookingchanges;

    /**
     * @param string $bookinguuid
     */
    public function __construct(string $bookinguuid)
    {
        $this->bookinguuid = $bookinguuid;
    }

    public function getBookinguuid(): string
    {
        return $this->bookinguuid;
    }

    public function setBookinguuid(string $bookinguuid): void
    {
        $this->bookinguuid = $bookinguuid;
    }

    public function getUseruuid(): \App\Class\Usuario
    {
        return $this->useruuid;
    }

    public function setUseruuid(\App\Class\Usuario $useruuid): void
    {
        $this->useruuid = $useruuid;
    }

    public function getBookingdate(): DateTime
    {
        return $this->bookingdate;
    }

    public function setBookingdate(DateTime $bookingdate): void
    {
        $this->bookingdate = $bookingdate;
    }

    public function getBookingunits(): int
    {
        return $this->bookingunits;
    }

    public function setBookingunits(int $bookingunits): void
    {
        $this->bookingunits = $bookingunits;
    }

    public function getBookingcost(): float
    {
        return $this->bookingcost;
    }

    public function setBookingcost(float $bookingcost): void
    {
        $this->bookingcost = $bookingcost;
    }

    public function getClientcode(): \App\Class\Cliente
    {
        return $this->clientcode;
    }

    public function setClientcode(\App\Class\Cliente $clientcode): void
    {
        $this->clientcode = $clientcode;
    }

    public function getBookingpaymethod(): MetodoPago
    {
        return $this->bookingpaymethod;
    }

    public function setBookingpaymethod(MetodoPago $bookingpaymethod): Reserva
    {
        $this->bookingpaymethod = $bookingpaymethod;
        return $this;
    }

    public function getBookingchanges(): int
    {
        return $this->bookingchanges;
    }

    public function setBookingchanges(int $bookingchanges): void
    {
        $this->bookingchanges = $bookingchanges;
    }
    public static function crearReservaAPartirDeUnArray(array $datosReserva):Reserva{

        $reserva = new Reserva(Uuid::uuid4());
        $reserva->setBookinguuid($datosReserva['bookinguuid']??Uuid::uuid4());
        $reserva->setBookingdate(DateTime::createFromFormat('d/m/Y',$datosReserva['bookingdate']));
        $reserva->setBookingunits($datosReserva['bookingunits']??0);
        //$reserva->setBookingcost($datosReserva['bookingcost']??0.0);
        //(No se si hay que ponerlo)$reserva->setClientcode($datosReserva['clientcode']??);
        $reserva->setBookingpaymethod(MetodoPago::convertirStringAMetodoPago(
            $datosReserva['bookingpaymethod']??null)??MetodoPago::CARD
        );
        return $reserva;
    }

    public function save():Reserva
    {
        ReservaModel::guardarReserva($this);
        return $this;
    }

    public function delete(){
        ReservaModel::borrarReserva($this);
    }

}