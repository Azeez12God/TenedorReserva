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
                    <form method="post" action="/bookings">
                        <div class="mb-3">
                            <label class="form-label" for="bookingdate">Fecha de la reserva</label>
                            <input class="form-control" id="bookingdate" name="bookingdate" type="text" placeholder="DD/MM/YYYY">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="bookingunits">Cantidad de personas</label>
                            <input class="form-control" id="bookingunits" name="bookingunits" type="number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="bookingpaymethod">Método de pago</label>
                            <select class="form-control" id="bookingpaymethod" name="bookingpaymethod">
                                <option>PayPal</option>
                                <option>Apple Pay</option>
                                <option>Google Pay</option>
                                <option>Tarjeta de crédito</option>
                                <option>Bizum</option>
                            </select>
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
