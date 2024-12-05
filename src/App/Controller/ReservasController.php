<?php

namespace App\Controller;

use App\Controller\InterfaceController;

class ReservasController implements InterfaceController
{
    //GET /bookings
    public function index($api){
        //include_once "../View/Users/indexUser.php";
    }

    //GET /bookings/create
    public function create($api){
        //Aquí mostraríamos el formulario de crear reserva
        include_once __DIR__."/../View/Booking/createBooking.php";
    }

    //POST /bookings
    public function store($api){
        //Guardaría en la base de datos el usuario
        echo "Función para guardar una reserva";
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