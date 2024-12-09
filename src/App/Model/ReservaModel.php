<?php

namespace App\Model;
use App\Class\Reserva;
use PDO;
use PDOException;

class ReservaModel
{
    private static function conectarBD():?PDO{
        try{
            $conexion = new PDO("mysql:host=".NOMBRE_CONTAINER_DATABASE.";dbname=".NOMBRE_DATABASE
                ,USUARIO_DATABASE,
                PASS_DATABASE);

            $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $conexion;
        }catch(PDOException $e){
            echo "Fallo de conexión".$e->getMessage();
        }

        return null;
    }

    private static function guardarReserva(Reserva $reserva){
        $conexion = ReservaModel::conectarBD();

        $sql = "INSERT INTO booking(bookinguuid, useruuid,bookingdate,bookingunits,bookingcost,
            clientcode, bookingpaymethod, bookingchanges) 
            values (:bookinguuid, :useruuid, :bookingdate, :bookingunits, :bookingcost,:clientcode,:bookingpaymethod,:bookingchanges)";

        $sentenciaPreparada = $conexion->prepare($sql);


        $sentenciaPreparada->bindValue("bookinguuid", $reserva->getBookingUuid());
        $sentenciaPreparada->bindValue(":bookingdate", $reserva->getBookingDate());
        $sentenciaPreparada->bindValue(":bookingunits", $reserva->getBookingUnits());
        $sentenciaPreparada->bindValue(":bookingpaymethod", $reserva->getBookingPayMethod());
        $sentenciaPreparada->bindValue(":bookingchanges", $reserva->getBookingChanges());
        $sentenciaPreparada->bindValue(":useruuid", $_SESSION["useruuid"]);
        $sentenciaPreparada->bindValue(":clientcolde", 0);
        $sentenciaPreparada->execute();
    }
}