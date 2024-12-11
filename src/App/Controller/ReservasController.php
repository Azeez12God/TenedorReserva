<?php

namespace App\Controller;

use App\Class\Reserva;
use App\Controller\InterfaceController;
use App\Excepcions\EditBookingException;
use App\Excepcions\ReadBookingException;
use App\Model\ReservaModel;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class ReservasController implements InterfaceController
{
    //GET /bookings
    public function index($api){
        include_once  __DIR__ . "/../View/Booking/indexBooking.php";
    }

    //GET /bookings/create
    public function create($api){
        include_once __DIR__ . "/../View/Booking/createBooking.php";
    }

    //POST /bookings
    public function store($api){
        //Guardaría en la base de datos el usuario
        $errores='';
        try {

            Validator::key('bookingdate', Validator::date('d/m/Y'))
                ->key('bookingunits', Validator::intType()->min(1)->max(99))
                ->key('bookingcost', Validator::floatType()->min(10)->max(50000))
                ->key('bookingpaymethod', Validator::notEmpty())
                ->assert($_POST);

        } catch (NestedValidationException $exception) {
            $errores=$exception->getMessages();
        }

        //Comprobamos si ha habido errores
        /*if (is_array($errores)){
            include_once __DIR__."/../View/Booking/errorBooking.php";
        }else{
            $reserva=Reserva::crearReservaAPartirDeUnArray($_POST);
            $reserva->save();
        }*/

        $reserva=Reserva::crearReservaAPartirDeUnArray($_POST);
        $reserva->save();


        if ($api){
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($reserva);
        }else{
            $informacion=['Ha habido un error.'];

            // De momento no hay session

            include_once DIRECTORIO_VISTAS."informacion.php";
        }

    }

    //GET /bookings/{id_reserva}/edit
    public function edit($id, $api){
        //Comprobar que el usuario exista y cargar los datos
        $reserva=ReservaModel::leerReserva($id);
        if (!$reserva){
            $errores[]="Usuario no encontrado";
            include_once DIRECTORIO_VISTAS."errores.php";
            exit();
        }else{
            $_SESSION["bookinguuid"] = $reserva->getBookinguuid();
            include_once DIRECTORIO_VISTAS."Booking/editBooking.php";
        }

    }


    //PUT /bookings/{id_reserva}
    public function update($id, $api){
        //Guardaría los datos modificados del usuario
        $reserva = ReservaModel::leerReserva($id);

        //Leer de un ficheros de datos los valore de PUT
        //No existe $_PUT
        parse_str(file_get_contents("php://input"),$datos_put_para_editar);

        //Filtraremos esos datos
        try {

            Validator::key('bookingdate', Validator::optional(Validator::date('d/m/Y')),false)
                ->key('bookingunits', Validator::optional(Validator::intType()->notEmpty()->max(999)),false)
                ->key('bookingcost', Validator::optional(Validator::floatType()),false)
                ->key('clientcode', Validator::optional(Validator::stringType()),false)
                ->key('bookingpaymethod', Validator::optional(Validator::stringType()),false)
                ->key('bookingchanges', Validator::optional(Validator::intType()),false)
                ->assert($datos_put_para_editar);

        } catch (NestedValidationException $exception) {
            $errores=$exception->getMessages();
        }

        //Los datos no tienen errores

        //Comprobación para transformar la fecha de string a DateTime
        if(isset($datos_put_para_editar['bookingdate'])){
            $reserva->setBookingdate(\DateTime::createFromFormat('d/m/Y',$datos_put_para_editar['bookingdate']));
        }
        $reserva->setBookingunits($datos_put_para_editar['bookingunits']??$reserva->getBookingunits());
        $reserva->setBookingcost($datos_put_para_editar['bookingcost']??$reserva->getBookingcost());
        /* Como no tenemos cliente va a dar error
        $reserva->setClientcode($datos_put_para_editar['clientcode']??$reserva->getClientcode());
        */
        $reserva->setBookingpaymethod($datos_put_para_editar['bookingpaymethod']??$reserva->getBookingpaymethod());
        $reserva->setBookingchanges($datos_put_para_editar['bookingchanges']??$reserva->getBookingchanges());


        //Almacenar los cambios en la base de datos
        try{
            //UsuarioModel::editarUsuario($usuario);
            $reserva->edit();
            if ($api){
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($reserva);
            }else{
                return true;
            }


        }catch (EditBookingException $e){
            if ($api){
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>'No se ha podido modificar la reserva'
                ]);
            }else{
                $e->getMessage();
            }

        }

    }


    //GET /bookings/{id_reserva}
    public function show($id, $api){
        try{
            $reserva=ReservaModel::leerReserva($id);
            if ($api){
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($reserva);
            }
        }catch(ReadBookingException $e){
            if($api){
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>"La reserva no se ha podido leer"
                ]);
            }else{
                $errores[]=$e->getMessage();
                include_once DIRECTORIO_VISTAS."errores.php";
            }

        }
    }


    //DELETE /bookings/{id_reserva}
    public function destroy($id, $api){
        echo "Función para borrar los datos de la reserva $id";
    }
}