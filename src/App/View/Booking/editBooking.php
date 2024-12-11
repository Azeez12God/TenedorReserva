<?php
$titulo="Edición de usuario";
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
                        <h1>Modificar Reserva</h1>
                        <p class="mx-auto">Cambia los datos de tu reserva</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-8 h-100">
                <div class="service">
                    <form method="post" action="/modificarreserva">
                        <input type="hidden" name="bookinguuid" value="<?=$id?>">
                        <div class="mb-3">
                            <label class="form-label" for="bookingdate">Fecha de la reserva</label>
                            <input class="form-control" id="bookingdate" name="bookingdate" type="text" placeholder="DD/MM/YYYY" value="<?=$reserva->getBookingdate()->format('d/m/Y')?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="bookingunits">Cantidad de personas</label>
                            <input class="form-control" id="bookingunits" name="bookingunits" type="number" value="<?=$reserva->getBookingunits()?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="bookingpaymethod">Método de pago</label>
                            <?php $selectedPayMethod = $reserva->getBookingpaymethod()->name; ?>
                            <select class="form-control" id="bookingpaymethod" name="bookingpaymethod">
                                <option value="PAYPAL" <?= $selectedPayMethod === "PAYPAL" ? 'selected' : '' ?>>PayPal</option>
                                <option value="APPLE_PAY" <?= $selectedPayMethod === "APPLE_PAY" ? 'selected' : '' ?>>Apple Pay</option>
                                <option value="GOOGLE_PAY" <?= $selectedPayMethod === "GOOGLE_PAY" ? 'selected' : '' ?>>Google Pay</option>
                                <option value="CARD" <?= $selectedPayMethod === "CARD" ? 'selected' : '' ?>>Tarjeta de crédito</option>
                                <option value="BIZUM" <?= $selectedPayMethod === "BIZUM" ? 'selected' : '' ?>>Bizum</option>
                            </select>
                        </div>
                        <input class="btn btn-brand ms-lg-3" value="Enviar" type="submit">
                        <a href="/borrarreserva" class="btn btn-danger ms-lg-5">Borrar Reserva</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php
include_once DIRECTORIO_VISTAS."template/footer.php";
include_once DIRECTORIO_VISTAS."template/modal.php";
include_once DIRECTORIO_VISTAS."template/final.php";