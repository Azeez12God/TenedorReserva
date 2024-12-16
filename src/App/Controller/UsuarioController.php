<?php

namespace App\Controller;
use App\Class\Enum\TipoUsuario;
use App\Class\Telefono;
use App\Class\Usuario;
use App\Controller\InterfaceController;
use App\Excepcions\DeleteUserException;
use App\Excepcions\ReadUserException;
use App\Model\UsuarioModel;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

include_once "InterfaceController.php";


class UsuarioController implements InterfaceController
{

    //GET /users
    public function index($api){
        if (isset($_GET['pag'])){
            $pag=$_GET['pag']-1;
        }else{
            $pag=0;
        }
        $usuarios= UsuarioModel::obtenerUsuarios(3,3*$pag);
        if ($api){
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($usuarios);
        }else{
            include_once DIRECTORIO_VISTAS."Users/indexUser.php";
        }
    }

    //GET /users/create
    public function create($api){
        //Aquí mostraríamos el formulario de registro
        include_once __DIR__."/../View/Users/createUser.php";

    }

    //POST /users
    public function store($api){
        //Validación del usuario
        $errores='';
        try {

            Validator::key('usernick', Validator::stringType()->notEmpty()->length(3, null))
                ->key('username', Validator::stringType())
                ->key('userdni', Validator::stringType()->length(9,9))
                ->key('usersurname', Validator::stringType())
                ->key('userpass', Validator::stringType()->length(8, null))
                ->key('useremail', Validator::email())
                ->key('userbirthdate', Validator::date('d/m/Y')->minAge(18, 'd/m/Y'))
                ->key('useradress', Validator::stringType())
                ->key('userphone', Validator::phone())
                ->key('useraltphone', Validator::optional(Validator::phone()),false)
                ->key('userdata', Validator::optional(Validator::json()),mandatory: false)
                ->assert($_POST);

        } catch (NestedValidationException $exception) {
            $errores=$exception->getMessages();
        }

        //Comprobamos si ha habido errores
        if (is_array($errores)){
            include_once __DIR__."/../View/Users/errorUser.php";
        }else{
            $usuario=Usuario::crearUsuarioAPartirDeUnArray($_POST);
        }

        //Guardamos el usuario
        $usuario->save();

        //Creación del usuario
        if ($api){
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($usuario);
        }else{
            $informacion=['Se ha creado el usuario correctamente'];
            $_SESSION['username']=$usuario->getUsername();
            $_SESSION['useruuid']=$usuario->getUuid();
            include_once DIRECTORIO_VISTAS."informacion.php";
        }

    }

    //GET /users/{id_usuario}/edit
    public function edit($id,$api){
       //Comprobar que el usuario exista y cargar los datos
        $usuario=UsuarioModel::leerUsuario($id);
        if (!$usuario){
            $errores[]="Usuario no encontrado";
            include_once DIRECTORIO_VISTAS."errores.php";
            exit();
        }else{
            include_once DIRECTORIO_VISTAS."Users/editUser.php";
        }



    }


    //PUT /users/{id_usuario}
    public function update($id,$api){
        //Guardaría los datos modificados del usuario
        $usuario = UsuarioModel::leerUsuario($id);

        //Leer de un ficheros de datos los valore de PUT
        //No existe $_PUT
        parse_str(file_get_contents("php://input"),$datos_put_para_editar);

        //Filtraremos esos datos
        try {
            Validator::key('usernick', Validator::optional(Validator::stringType()->notEmpty()->length(3, null)),false)
                ->key('username', Validator::optional(Validator::stringType()),false)
                ->key('userdni', Validator::optional(Validator::stringType()->length(9,9)),false)
                ->key('usersurname', Validator::optional(Validator::stringType()),false)
                ->key('userpass', Validator::optional(Validator::stringType()->length(8, null)),false)
                ->key('useremail', Validator::optional(Validator::email()),false)
                ->key('userbirthdate', Validator::optional(Validator::date('d/m/Y')->minAge(18, 'd/m/Y')),false)
                ->key('useradress', Validator::optional(Validator::stringType()),false)
                ->key('userphone', Validator::optional(Validator::phone()),false)
                ->key('useraltphone', Validator::optional(Validator::phone()),false)
                ->key('userdata', Validator::optional(Validator::json()), false)
                ->assert($datos_put_para_editar);

        } catch (NestedValidationException $exception) {
            $errores=$exception->getMessages();
        }

        //Los datos no tienen errores

        $usuario->setUsername($datos_put_para_editar['usernick']??$usuario->getUsername());
        $usuario->setNombre($datos_put_para_editar['username']??$usuario->getNombre());
        $usuario->setApellidos($datos_put_para_editar['usersurname']??$usuario->getApellidos());
        $usuario->setCorreoelectronico($datos_put_para_editar['useremail']??$usuario->getCorreoelectronico());
        $usuario->setDireccion($datos_put_para_editar['useradress']??$usuario->getDireccion());
        $usuario->setDni($datos_put_para_editar['userdni']??$usuario->getDni());
        $usuario->setDatosAdicionales($datos_put_para_editar['userdata']??$usuario->getDatosAdicionales());
        $usuario->setCalificacion($datos_put_para_editar['usermark']??$usuario->getCalificacion());
        $usuario->setTarjetaPago($datos_put_para_editar['usercard']??$usuario->getTarjetaPago());

        //Comprobación para cifrar el nuevo password
        if (isset($datos_put_para_editar['userpass'])){
            $usuario->setPassword(password_hash($datos_put_para_editar['userpass'],PASSWORD_DEFAULT));
        }

        //Comprobación para transformar la fecha de string a DateTime
        if(isset($datos_put_para_editar['userbirthdate'])){
            $usuario->setFechanac(\DateTime::createFromFormat('d/m/Y',$datos_put_para_editar['userbirthdate']));
        }

        //Gestión de los teléfonos asociados al usuario
        $telefonos=$usuario->getTelefonos();
        if (isset($datos_put_para_editar['userphone'])){
            $telefonos[0]=Telefono::crearTelefonoDesdeString($datos_put_para_editar['userphone']);
        }
        if (isset($datos_put_para_editar['useraltphone'])){
            $telefonos[1]=Telefono::crearTelefonoDesdeString($datos_put_para_editar['userphone']);
        }

        $usuario->setTelefonos($telefonos);

        if (isset($datos_put_para_editar['usertype'])){
            $usuario->setTipo(TipoUsuario::convertirStringATipoUsuario($datos_put_para_editar['usertype']));
        }


        //Almacenar los cambios en la base de datos
        try{
            //UsuarioModel::editarUsuario($usuario);
            $usuario->edit();
            if ($api){
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($usuario);
            }else{
                echo '';
            }


        }catch (EditUserException $e){
            if ($api){
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>'El usuario no se ha podido editar'
                ]);
            }else{
                $e->getMessage();
            }

        }

    }


    //GET /users/{id_usuario}
    public function show($id, $api){
        //Mostraría los datos de un solo usuario
        try{
            $usuario=UsuarioModel::leerUsuario($id);
            if ($api){
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($usuario);
            }else{
                include_once DIRECTORIO_VISTAS."Users/editUser.php";
            }
        }catch(ReadUserException $e){
            if($api){
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>"El usuario no se ha podido leer"
                ]);
            }else{
                $errores[]=$e->getMessage();
                include_once DIRECTORIO_VISTAS."errores.php";
            }

        }
    }


    //DELETE /users/{id_usuario}
    public function destroy($id,$api){
        //Borrar los datos de un usuario
        try {
            UsuarioModel::borrarUsuario($id);
            //Si api==false
            if ($api){
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>"El usuario ha sido borrado correctamente"
                ]);
            }
            return true;
        }catch (DeleteUserException $e){
            if($api){
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    "mensaje"=>"El usuario NO ha sido borrado"
                ]);
            }
            $errores[]=$e->getMessage();
            include_once DIRECTORIO_VISTAS."errores.php";
        }
    }

    public function verify(){
        //Tenemos que leer los datos del usuario que me han llegado por $_POST
        if (isset($_POST['username']) && isset($_POST['userpass'])){
            //Tenemos que verificar que el usuario exista y que la contrasña sea correcta
            $usuario= UsuarioModel::comprobarUsuario($_POST['username']);
            if (password_verify($_POST['userpass'],$usuario->getPassword())){
               //session_start();
               $_SESSION['username']=$usuario->getUsername();
               $_SESSION['useruuid']=$usuario->getUuid();
               header('Location: /');
               exit();
            }else{
                $errores=['Login invalido'];
                //TODO modificar la vista Login para que se muestren los errores y se rellene el usuario
                echo "pass_incorrecto";
            }

            //Tenemos que guardar en la session $_SESSION algo
            // que nos permita identificar que el usuario está logeado
        }else{
            $errores=['Login invalido'];
            //TODO modificar la vista Login para que se muestren los errores y se rellene el usuario
            echo "usuario incorrecto";
        }


    }

}