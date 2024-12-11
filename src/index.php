<?php

include_once "environment.php";
include_once "vendor/autoload.php";

use App\Router\Router;
use App\Controller\UsuarioController;

setcookie("ultima","index",time()+3600,"/","localhost",false,true);
session_start();


$router = new Router();


//Rutas de páginas estáticas
$router->addRoute('get','/',function(){
    include_once DIRECTORIO_VISTAS."landing.php";
});
$router->addRoute('get','/about',function(){
    include_once DIRECTORIO_VISTAS."about.php";
});
$router->addRoute('get','/login',function(){
    include_once DIRECTORIO_VISTAS . "login.php";
});

$router->addRoute('get','/services',function(){
    include_once DIRECTORIO_VISTAS . "services.php";
});

$router->addRoute('get','/contact',function(){
    include_once DIRECTORIO_VISTAS."contacto.php";
});

$router->addRoute("get","/logout",function(){
    unset($_SESSION['username']);
    session_destroy();
    header('Location: /');
});

$router->addRoute("post","/modificaruser",function(){
    //Tomar los datos de post y realizar la petición PUT

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/users/'.$_POST['useruuid'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => 'usernick='.$_POST['usernick'].
                                '&userpass='.$_POST['userpass'].
                                '&userdni='.$_POST['userdni'].
                                '&username='.$_POST['username'].
                                '&usersurname='.$_POST['usersurname'].
                                '&useradress='.$_POST['useradress'].
                                '&useremail='.$_POST['useremail'].
                                '&userbirthdate='.$_POST['userbirthdate'].
                                '&userphone='.$_POST['userphone'].
                                '&useraltphone='.$_POST['useraltphone'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    if ($response==''){
        header('Location: /users/'.$_POST['useruuid']."/edit");
        exit();
    }
});

$router->addRoute('get','/borraruser',function(){

    $client = new \GuzzleHttp\Client();
    $request = new \GuzzleHttp\Psr7\Request('DELETE', 'http://localhost/users/'.$_SESSION['useruuid']);
    $res = $client->sendAsync($request)->wait();

    unset($_SESSION['username']);
    unset($_SESSION['useruuid']);
    session_destroy();
    header("Location: /");
    exit();

});
$router->addRoute('get','/ia',function(){
    $client = \ArdaGnsrn\Ollama\Ollama::client('http://ollama:11434');

    $usuario = \App\Model\UsuarioModel::leerUsuario('0ceb27b1-6281-4b28-b3b3-ac7507ba7c00');

    $response = $client->chat()->create([
        'model' => 'tinyllama',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres una IA para una Web de Reservas'],
            ['role' => 'user', 'content' => 'Hola!'],
            ['role' => 'assistant', 'content' => 'Crea una clasificacion del 1 al 100 de lo completos que son los datos del usuario que recibirás a continuación'],
            ['role' => 'user', 'content' => json_encode($usuario)],
        ],
    ]);

    echo $response->message->content;
});


//Rutas enlazadas a controladores, lógica de la aplicación
//Usuarios
$router->addRoute('get','/users',[\App\Controller\UsuarioController::class,'index']);
$router->addRoute('get','/users/create',[\App\Controller\UsuarioController::class,'create']);
$router->addRoute('post','/users',[\App\Controller\UsuarioController::class,'store']);
$router->addRoute('get','/users/{id}/edit',[\App\Controller\UsuarioController::class,'edit']);
$router->addRoute('put','/users/{id}',[\App\Controller\UsuarioController::class,'update']);
$router->addRoute('get','/users/{id}',[\App\Controller\UsuarioController::class,'show']);
$router->addRoute('delete','/users/{id}',[\App\Controller\UsuarioController::class,'destroy']);

$router->addRoute('post','/users/verify',[UsuarioController::class,'verify']);


//Usuario API
$router->addRoute('post','/api/users/{id}',[\App\Controller\UsuarioController::class,'store']);
$router->addRoute('get','/api/users/{id}',[\App\Controller\UsuarioController::class,'index']);
$router->addRoute('put','/api/users/{id}',[\App\Controller\UsuarioController::class,'update']);
$router->addRoute('delete','/api/users/{id}',[\App\Controller\UsuarioController::class,'destroy']);




//Clientes
$router->addRoute('get','/clients',[\App\Controller\ClienteController::class,'index']);
$router->addRoute('get','/clients/create',[\App\Controller\ClienteController::class,'create']);
$router->addRoute('post','/clients',[\App\Controller\ClienteController::class,'store']);
$router->addRoute('get','/clients/{id}/edit',[\App\Controller\ClienteController::class,'edit']);
$router->addRoute('put','/clients/{id}',[\App\Controller\ClienteController::class,'update']);
$router->addRoute('get','/clients/{id}',[\App\Controller\ClienteController::class,'show']);
$router->addRoute('delete','/clients/{id}',[\App\Controller\ClienteController::class,'destroy']);

//Reservas
$router->addRoute('get','/bookings',[\App\Controller\ReservasController::class,'index']);
$router->addRoute('get','/bookings/create',[\App\Controller\ReservasController::class,'create']);
$router->addRoute('post','/bookings',[\App\Controller\ReservasController::class,'store']);
$router->addRoute('get','/bookings/{id}/edit',[\App\Controller\ReservasController::class,'edit']);
$router->addRoute('put','/bookings/{id}',[\App\Controller\ReservasController::class,'update']);
$router->addRoute('get','/bookings/{id}',[\App\Controller\ReservasController::class,'show']);
$router->addRoute('delete','/bookings/{id}',[\App\Controller\ReservasController::class,'destroy']);

$router->addRoute("post","/modificarreserva",function(){
    //Tomar los datos de post y realizar la petición PUT

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost/bookings/'.$_POST['bookinguuid'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => 'bookingdate='.$_POST['bookingdate'].
            '&bookingunits='.$_POST['bookingunits'].
            '&bookingpaymethod='.$_POST['bookingpaymethod'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    if ($response==''){
        header('Location: /bookings/'.$_POST['bookinguuid']."/edit");
        exit();
    }
});

$router->resolver($_SERVER['REQUEST_METHOD'],$_SERVER['REQUEST_URI']);