<?php
$titulo = "ReserDAWtions";
include_once "./environment.php";
include_once DIRECTORIO_VISTAS . "template/inicio.php";
include_once DIRECTORIO_VISTAS . "template/arribaNavegacion.php";
include_once DIRECTORIO_VISTAS . "template/navegacion.php";
?>

<section id="services" class="text-center">
    <div class="container">
        <h1>Listado de Reservas</h1>
        <?php if (!empty($reservas)): ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cantidad de personas</th>
                    <th>Método de Pago</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reservas as $reserva): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['bookinguuid']) ?></td>
                        <td><?= htmlspecialchars($reserva['bookingdate']) ?></td>
                        <td><?= htmlspecialchars($reserva['bookingunits']) ?></td>
                        <td><?= htmlspecialchars($reserva['bookingpaymethod']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay reservas disponibles.</p>
        <?php endif; ?>
    </div>
</section>

<?php
include_once DIRECTORIO_VISTAS . "template/footer.php";
include_once DIRECTORIO_VISTAS . "template/modal.php";
include_once DIRECTORIO_VISTAS . "template/final.php";
?>
