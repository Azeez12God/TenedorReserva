<?php

namespace App\Model;
use App\Class\Reserva;
use App\Excepcions\DeleteBookingException;
use App\Excepcions\ReadBookingException;
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

    public static function guardarReserva(Reserva $reserva){
        $conexion = ReservaModel::conectarBD();

        $sql = "INSERT INTO booking(bookinguuid, useruuid,bookingdate,bookingunits,bookingcost,
            clientcode, bookingpaymethod, bookingchanges) 
            values (:bookinguuid, :useruuid,STR_TO_DATE(:bookingdate, '%d/%m/%Y'), :bookingunits, :bookingcost,:clientcode,:bookingpaymethod,:bookingchanges)";

        $sentenciaPreparada = $conexion->prepare($sql);


        $sentenciaPreparada->bindValue(":bookinguuid", $reserva->getBookingUuid());
        $sentenciaPreparada->bindValue(":bookingdate", $reserva->getBookingDate()->format('d/m/Y'));
        $sentenciaPreparada->bindValue(":bookingunits", $reserva->getBookingUnits());
        $sentenciaPreparada->bindValue(":bookingcost", 0.0);
        $sentenciaPreparada->bindValue(":bookingpaymethod", $reserva->getBookingPayMethod()->name);
        $sentenciaPreparada->bindValue(":bookingchanges", 0 );
        $sentenciaPreparada->bindValue(":useruuid", $_SESSION["useruuid"]);
        $sentenciaPreparada->bindValue(":clientcode", 0);
        $sentenciaPreparada->execute();
    }

    public static function leerReserva($bookinguuid):?Reserva{
        //Crear una conexión con la base de datos
        $conexion = ReservaModel::conectarBD();

        //Crear una variable con la sentencia SQL que queremos ejecutar
        $sql = "SELECT bookinguuid,DATE_FORMAT(bookingdate,'%d/%m/%Y') as bookingdate,
        useruuid,bookingunits,bookingcost,clientcode,bookingpaymethod,bookingchanges 
        FROM booking where bookinguuid=:uuid";

        //Preparar la sentencia a ejecutar
        $sentenciaPreparada=$conexion->prepare($sql);

        //Hacer la asignación de los parametros de la SQL al valor
        $sentenciaPreparada->bindValue('uuid',$bookinguuid);

        //Ejecutar la consulta con los parametros ya cambiados en la base de datos
        $sentenciaPreparada->execute();

        if($sentenciaPreparada->rowCount()===0){
            //Se ha producido un error
            throw new ReadBookingException();
        }else{
            //Leer de la base datos un usuario
            $datosReserva = $sentenciaPreparada->fetch(PDO::FETCH_ASSOC);

            /*(No tenemos aún la tabla client)
            $sqlclientcode = "SELECT clientcode FROM client WHERE useruuid=?";
            $sentenciaCodigoCliente = $conexion->prepare($sqlclientcode);
            $sentenciaCodigoCliente->execute([$bookinguuid]);
            */

            $reserva=Reserva::crearReservaAPartirDeUnArray($datosReserva);
            return $reserva;
        }
    }

    public static function borrarReserva(string $bookinguuid):bool
    {
       $conexion = ReservaModel::conectarBD();

       $sql = "DELETE FROM booking where bookinguuid=?";

       $sentenciaPreparada=$conexion->prepare($sql);
       $sentenciaPreparada->bindValue(1,$bookinguuid);
       $sentenciaPreparada->execute();

       if($sentenciaPreparada->rowCount()===0){
           throw new DeleteBookingException();
       }else{
           return true;
       }
    }
}