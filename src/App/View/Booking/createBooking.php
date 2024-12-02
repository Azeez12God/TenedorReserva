<?php
$titulo="Registro de usuario";
include_once "./environment.php";
include_once DIRECTORIO_VISTAS."template/inicio.php";
include_once DIRECTORIO_VISTAS."template/arribaNavegacion.php";
include_once DIRECTORIO_VISTAS."template/navegacion.php";
?>

    <section id="services" class="text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="intro">
                        <h1>Registrar Reserva</h1>
                        <p class="mx-auto">Introduce los datos de la reserva</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-8 h-100">
                <div class="service">
                    <form method="post" action="/users">
                        <div class="mb-3">
                            <label class="form-label" for="user">Nick de usuario</label>
                            <input class="form-control" id="user" name="usernick" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="dni">DNI del usuario</label>
                            <input class="form-control" id="dni" name="userdni" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Nombre de usuario</label>
                            <input class="form-control" id="name" name="username" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="surname">Apellidos de usuario</label>
                            <input class="form-control" id="surname" name="usersurname" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pass">Contraseña</label>
                            <input class="form-control" id="pass" name="userpass" type="password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo electrónico</label>
                            <input class="form-control" id="email" name="useremail" type="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="birthdate">Fecha de nacimiento</label>
                            <input class="form-control" id="birthdate" name="userbirthdate" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="adress">Dirección</label>
                            <input class="form-control" id="adress" name="useradress" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Teléfono de contacto</label>
                            <input class="form-control" id="phone" name="userphone" type="tel">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="altphone">Teléfono alternativo</label>
                            <input class="form-control" id="altphone" name="useraltphone" type="tel">
                        </div>
                        <input class="btn btn-brand ms-lg-3" value="Enviar" type="submit">
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php
include_once DIRECTORIO_VISTAS."template/footer.php";
include_once DIRECTORIO_VISTAS."template/modal.php";
include_once DIRECTORIO_VISTAS."template/final.php";
