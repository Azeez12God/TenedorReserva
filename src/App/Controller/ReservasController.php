<?php

namespace App\Controller;

use App\Class\Reserva;
use App\Controller\InterfaceController;
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
        //Mostraría un formulario con los datos del usuario
        echo "Formulario para editar los datos de la reserva $id";

    }


    //PUT /bookings/{id_reserva}
    public function update($id, $api){
        //Guardaría los datos modificados del usuario
        echo "Función para actualizar los datos en la BD de la reserva $id";

    }


    //GET /bookings/{id_reserva}
    public function show($id, $api){
        echo "Mostar los datos de la reserva $id";
    }


    //DELETE /bookings/{id_reserva}
    public function destroy($id, $api){
        echo "Función para borrar los datos de la reserva $id";
    }
}