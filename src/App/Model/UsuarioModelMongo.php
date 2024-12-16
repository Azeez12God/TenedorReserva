<?php

namespace App\Model;

use App\Class\Usuario;
use MongoDB\Client;

class UsuarioModelMongo
{
    public static function guardarUsuario(Usuario $usuario){

        $conexion = new Client('mongodb://root:example@mongo:27017');

        $basedatos = $conexion->test;

        $coleccion = $basedatos->users;

        $resultado = $coleccion->insertOne($usuario->jsonSerialize());

    }

    public static function borrarUsuario(string $uuid){
        $conexion = new Client('mongodb://root:example@mongo:27017');

        $basedatos = $conexion->test;

        $coleccion = $basedatos->users;

        $resultado = $coleccion->deleteOne(["useruuid"=>$uuid]);

    }

    public static function cargarUsuario(string $uuid){
        $conexion = new Client('mongodb://root:example@mongo:27017');

        $basedatos = $conexion->test;

        $coleccion = $basedatos->users;

        $resultado = $coleccion->findOne(['useruuid'=>$uuid]);
    }

}