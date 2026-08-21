<?php
require_once 'MascotaDAO.php';
require_once 'paginacionf.php';

$dao = new MascotaDAO();

// 1. Obtener el número total de registros
$totalRegistros = $dao->obtenerTotalMascotas();

// 2. Instanciar la clase de paginación (5 registros por página)
$paginador = new Paginacion($totalRegistros, 5);

// 3. Obtener solo las mascotas de la página actual
$listaMascotas = $dao->obtenerMascotasPaginadas($paginador->getInicio(), $paginador->getLimite());
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mascotas Paginadas</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="w3-container w3-padding-24">
    <h2>Directorio de Mascotas</h2>
    <table class="w3-table-all w3-hoverable">
        <thead>
            <tr class="w3-teal">
                <th>ID</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Edad</th>
                <th>Peso</th>
                <th>Responsable</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaMascotas)): ?>
                <tr>
                    <td colspan="8" class="w3-center">No hay registros disponibles.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($listaMascotas as $m): ?>
                    <tr>
                        <td>#<?php echo $m['id']; ?></td>
                        <td><?php echo $m['nombre']; ?></td>
                        <td><?php echo $m['especie']; ?></td>
                        <td><?php echo $m['raza']; ?></td>
                        <td><?php echo $m['edad']; ?> años</td>
                        <td><?php echo $m['peso_actual']; ?> Kg</td>
                        <td><?php echo $m['nombre_responsable']; ?></td>
                        <td><?php echo $m['telefono_emergencia']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Renderizar la barra de navegación de páginas -->
    <?php $paginador->renderizarBotones(); ?>
</div>

</body>
</html>