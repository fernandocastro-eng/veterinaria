<?php
require_once "clases/MascotaDAO.php";
require_once "clases/Mascota.php";

function limpiarEntrada($datos) {
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}

$mensajeAlerta = "";
$dao = new MascotaDAO();

// Guardar mascota al presionar el botón
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_guardar'])) {
    try {
        $nombre      = limpiarEntrada($_POST['nombre']);
        $especie     = limpiarEntrada($_POST['especie']);
        $raza        = limpiarEntrada($_POST['raza']);
        $edad        = (int)limpiarEntrada($_POST['edad']);
        $peso        = (float)limpiarEntrada($_POST['peso']);
        $color       = limpiarEntrada($_POST['color_senas']);
        $responsable = limpiarEntrada($_POST['nombre_responsable']);
        $telefono    = limpiarEntrada($_POST['telefono_emergencia']);

        $nuevaMascota = new Mascota($nombre, $especie, $raza, $edad, $peso, $color, $responsable, $telefono);
        $resultado    = $dao->guardarMascota($nuevaMascota);

        if ($resultado['exito']) {
            $mensajeAlerta = '<div class="w3-panel w3-green w3-padding-16 w3-round">' . $resultado['mensaje'] . '</div>';
        } else {
            $mensajeAlerta = '<div class="w3-panel w3-red w3-padding-16 w3-round">' . $resultado['mensaje'] . '</div>';
        }
    } catch (Exception $e) {
        $mensajeAlerta = '<div class="w3-panel w3-amber w3-padding-16 w3-round">Error de Validación: ' . $e->getMessage() . '</div>';
    }
}

// LÓGICA DE PAGINACIÓN (IGUAL A MISITIOF)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) { 
    $pagina = 1; 
}
$cantRegistros = 5; // Registros a mostrar por página
$inicio = ($pagina > 1) ? (($pagina * $cantRegistros) - $cantRegistros) : 0;

$totalMascotas  = $dao->obtenerTotalMascotas();
$listaMascotas  = $dao->obtenerMascotasPaginadas($inicio, $cantRegistros);
$numeroPaginas  = ceil($totalMascotas / $cantRegistros);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santuario de Mascotas - Panel</title>
    <link rel="stylesheet" href="w3.css">
</head>
<body>

<?php 
// Mostrar alerta si existe un mensaje
if (!empty($mensajeAlerta)) {
    echo $mensajeAlerta;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santuario de Mascotas - Panel de Control</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="w3.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

<nav class="w3-sidebar w3-bar-block" id="mySidebar">
    <div class="w3-container w3-padding-24 w3-center w3-border-bottom w3-border-light-grey">
        <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=150&auto=format&fit=crop&q=60" alt="Logo Centro" class="w3-circle w3-border" style="width:70px; height:70px; object-fit:cover;">
        <h4 class="w3-bold w3-margin-top-small w3-margin-none w3-text-dark-grey">Santuario Hub</h4>
        <span class="w3-tag w3-round-pill w3-light-grey w3-text-blue w3-tiny w3-bold w3-margin-top">v2.0 Digital</span>
    </div>

    <div class="w3-padding-12">
        <a href="#lista-pacientes" class="w3-bar-item w3-button w3-padding-12">
            <i class="fa fa-list w3-margin-right w3-text-blue"></i><b>Directorio Pacientes</b>
        </a>
        <a href="#registro-pacientes" class="w3-bar-item w3-button w3-padding-12">
            <i class="fa fa-plus-circle w3-margin-right w3-text-blue"></i><b>Nuevo Registro</b>
        </a>
        <a href="javascript:void(0)" onclick="document.getElementById('modalAuth').style.display='block'" class="w3-bar-item w3-button w3-padding-12 w3-margin-top">
            <i class="fa fa-user-circle w3-margin-right w3-text-blue"></i><b>Acceso Usuario</b>
        </a>
    </div>
</nav>

<div class="w3-main">

    <header class="w3-container w3-white w3-border-bottom w3-border-light-grey w3-padding-16">
        <button class="w3-button w3-white w3-hide-large w3-left w3-margin-right w3-large" onclick="w3_open()"><i class="fa fa-bars"></i></button>
        <div class="w3-left">
            <h3 class="w3-margin-none w3-bold w3-text-dark-grey">Santuario de Mascotas</h3>
            <p class="w3-margin-none w3-small w3-text-grey">Expediente Maestro Digital</p>
        </div>
        <div class="w3-right w3-hide-small">
            <span class="w3-tag w3-round-pill w3-white w3-border w3-border-light-grey w3-padding-large w3-text-dark-grey">
                <i class="fa fa-calendar-check w3-margin-right w3-text-blue"></i><?php echo date("d/m/Y"); ?>
            </span>
        </div>
    </header>

    <div class="w3-container w3-padding-24">

        <?php if (!empty($mensajeAlerta)): ?>
            <div class="w3-margin-bottom">
                <?php echo $mensajeAlerta; ?>
            </div>
        <?php endif; ?>

        <div class="w3-card-clean w3-margin-bottom">
            <div class="w3-card-header w3-display-container">
                <span class="w3-tag w3-round-pill w3-light-grey w3-text-red w3-right w3-small w3-padding-small w3-bold"><i class="fa fa-exclamation-circle w3-margin-right"></i>Ficha Seleccionada</span>
                <h4 class="w3-margin-none w3-bold w3-text-dark-grey"><i class="fa fa-file-medical w3-margin-right w3-text-blue"></i>Expediente Clínico del Paciente</h4>
            </div>

            <div class="w3-container w3-padding-24">
                <div class="w3-row-padding">
                    <div class="w3-col m3 w3-center w3-margin-bottom">
                        <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=300&auto=format&fit=crop&q=60" class="w3-round-large w3-border w3-image" alt="Paciente" style="max-height:170px; width:100%; object-fit:cover;">
                        <button class="w3-btn-clean w3-block w3-margin-top" onclick="generarPDFDesdeVista()"><i class="fa fa-file-pdf w3-margin-right"></i>Generar PDF</button>
                    </div>

                    <div class="w3-col m9">
                        <div class="w3-row">
                            <div class="w3-half">
                                <h3 class="w3-bold w3-text-dark-grey w3-margin-none" id="pdf_nombre">Rocky</h3>
                                <p class="w3-text-grey w3-margin-none"><b>Especie:</b> <span id="pdf_especie">Canino</span> | <b>Raza:</b> <span id="pdf_raza">Golden Retriever</span></p>
                                <p class="w3-text-grey"><b>Responsable:</b> <span id="pdf_responsable">Carlos Mendoza</span> | <b>Teléfono:</b> <span id="pdf_telefono">9900-1234</span></p>
                            </div>
                            <div class="w3-half w3-right-align w3-hide-small">
                                <span class="w3-tag w3-round-pill w3-light-grey w3-text-dark-grey w3-padding-large">Peso Actual: <b id="pdf_peso">28.5 Kg</b></span>
                            </div>
                        </div>

                        <div class="w3-row-padding w3-margin-top">
                            <div class="w3-third w3-margin-bottom">
                                <div class="w3-card w3-round-large w3-padding w3-white w3-border w3-border-light-grey">
                                    <h5 class="w3-bold w3-text-dark-grey w3-small"><i class="fa fa-paw w3-margin-right w3-text-blue"></i>Detalles</h5>
                                    <p class="w3-tiny w3-margin-none"><b>Edad:</b> <span id="pdf_edad">3</span> años</p>
                                    <p class="w3-tiny w3-margin-none"><b>Señas:</b> <span id="pdf_color">Dorado, mancha en pecho</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="lista-pacientes" class="w3-card-clean w3-margin-bottom">
            <div class="w3-card-header">
                <h4 class="w3-margin-none w3-bold w3-text-dark-grey">
                    <i class="fa fa-list w3-margin-right w3-text-blue"></i>Pacientes Registrados en la Base de Datos
                </h4>
            </div>
            <div class="w3-container w3-padding-16 w3-responsive">
                <table class="w3-table w3-striped w3-bordered w3-hoverable w3-white">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Edad</th>
                            <th>Peso</th>
                            <th>Color / Señas</th>
                            <th>Responsable</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listaMascotas)): ?>
                            <tr>
                                <td colspan="10" class="w3-center w3-text-grey">No hay mascotas registradas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listaMascotas as $m): ?>
                                <tr>
                                    <td><b>#<?php echo $m['id']; ?></b></td>
                                    <td><?php echo $m['nombre']; ?></td>
                                    <td><?php echo $m['especie']; ?></td>
                                    <td><?php echo $m['raza']; ?></td>
                                    <td><?php echo $m['edad']; ?> años</td>
                                    <td><?php echo $m['peso_actual']; ?> Kg</td>
                                    <td><?php echo $m['color_senas']; ?></td>
                                    <td><?php echo $m['nombre_responsable']; ?></td>
                                    <td><?php echo $m['telefono_emergencia']; ?></td>
                                    <td>
                                        <button class="w3-btn-secondary w3-small" onclick='cargarMascota(<?php echo json_encode($m); ?>)'>
                                            <i class="fa fa-eye"></i> Ver
                                        </button>
                                        <button class="w3-btn-clean w3-small" onclick='descargarPDFDirecto(<?php echo json_encode($m); ?>)'>
                                            <i class="fa fa-file-pdf"></i> PDF
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- BARRA DE PAGINACIÓN (IGUAL A MISITIOF) -->
                <div class="w3-center w3-margin-top">
                    <div class="w3-bar">
                        <!-- Botón Anterior («) -->
                        <?php if ($pagina == 1): ?>
                            <a href="#" class="w3-bar-item w3-button w3-border w3-teal w3-disabled">&laquo;</a>
                        <?php else: ?>
                            <a href="index.php?pagina=<?php echo $pagina - 1; ?>" class="w3-bar-item w3-button w3-border w3-teal">&laquo;</a>
                        <?php endif; ?>

                        <!-- Botones Números de Página -->
                        <?php for ($i = 1; $i <= $numeroPaginas; $i++): ?>
                            <?php if ($pagina == $i): ?>
                                <a href="index.php?pagina=<?php echo $i; ?>" class="w3-bar-item w3-button w3-border w3-dark-grey"><?php echo $i; ?></a>
                            <?php else: ?>
                                <a href="index.php?pagina=<?php echo $i; ?>" class="w3-bar-item w3-button w3-border"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Botón Siguiente (») -->
                        <?php if ($pagina == $numeroPaginas || $numeroPaginas == 0): ?>
                            <a href="#" class="w3-bar-item w3-button w3-border w3-teal w3-disabled">&raquo;</a>
                        <?php else: ?>
                            <a href="index.php?pagina=<?php echo $pagina + 1; ?>" class="w3-bar-item w3-button w3-border w3-teal">&raquo;</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div id="registro-pacientes" class="w3-card-clean w3-margin-bottom">
            <div class="w3-card-header">
                <h4 class="w3-margin-none w3-bold w3-text-dark-grey">
                    <i class="fa fa-plus-circle w3-margin-right w3-text-blue"></i>Registro de Mascotas Rescatadas
                </h4>
            </div>

            <form class="w3-container w3-padding-24" method="POST" action="">
                <div class="w3-row-padding w3-margin-bottom">
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">1. Nombre de la Mascota</label>
                        <input class="w3-input-clean" type="text" name="nombre" placeholder="Ej: Max" required>
                    </div>
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">2. Especie</label>
                        <select class="w3-input-clean" name="especie" required>
                            <option value="" disabled selected>Seleccione la especie</option>
                            <option value="Canino">Canino</option>
                            <option value="Felino">Felino</option>
                            <option value="Ave">Ave</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">3. Raza</label>
                        <input class="w3-input-clean" type="text" name="raza" placeholder="Ej: Mestizo / Labrador" required>
                    </div>
                </div>

                <div class="w3-row-padding w3-margin-bottom">
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">4. Edad (Años)</label>
                        <input class="w3-input-clean" type="number" name="edad" min="0" placeholder="Ej: 2" required>
                    </div>
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">5. Peso Actual (Kg)</label>
                        <input class="w3-input-clean" type="number" step="0.01" name="peso" placeholder="Debe ser mayor a 0" required>
                    </div>
                    <div class="w3-third w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">6. Color o Señas Físicas</label>
                        <input class="w3-input-clean" type="text" name="color_senas" placeholder="Ej: Café con pecho blanco" required>
                    </div>
                </div>

                <div class="w3-row-padding w3-margin-bottom">
                    <div class="w3-half w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">7. Nombre del Responsable</label>
                        <input class="w3-input-clean" type="text" name="nombre_responsable" placeholder="Ej: Juan Pérez" required>
                    </div>
                    <div class="w3-half w3-margin-bottom">
                        <label class="w3-text-dark-grey w3-bold w3-small">8. Teléfono de Emergencia</label>
                        <input class="w3-input-clean" type="text" name="telefono_emergencia" placeholder="Ej: 9988-7766" required>
                    </div>
                </div>

                <div class="w3-container w3-padding-16 w3-border-top w3-border-light-grey w3-right-align">
                    <button type="reset" class="w3-btn-secondary w3-margin-right">Limpiar Campos</button>
                    <button type="submit" name="btn_guardar" class="w3-btn-clean"><i class="fa fa-save w3-margin-right"></i>Guardar Mascota</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="modalAuth" class="w3-modal">
    <div class="w3-modal-content w3-card-clean w3-animate-zoom" style="max-width:400px;">
        <header class="w3-card-header w3-display-container">
            <span onclick="document.getElementById('modalAuth').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h4 class="w3-margin-none w3-bold w3-text-dark-grey"><i class="fa fa-lock w3-margin-right w3-text-blue"></i>Acceso Usuario</h4>
        </header>

        <div class="w3-container w3-padding-24">
            <label class="w3-text-dark-grey w3-small w3-bold">Correo Electrónico</label>
            <input class="w3-input-clean w3-margin-bottom" type="email" placeholder="usuario@veticlinic.com">
            
            <label class="w3-text-dark-grey w3-small w3-bold">Contraseña</label>
            <input class="w3-input-clean w3-margin-bottom" type="password" placeholder="******">
            
            <button class="w3-btn-clean w3-block w3-margin-top" onclick="document.getElementById('modalAuth').style.display='none'">Ingresar</button>
        </div>
    </div>
</div>

<script>
function toggleSubmenu(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") === -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

function w3_open() {
    var sidebar = document.getElementById("mySidebar");
    if (sidebar.style.display === 'block') {
        sidebar.style.display = 'none';
    } else {
        sidebar.style.display = 'block';
    }
}

function cargarMascota(m) {
    document.getElementById("pdf_nombre").innerText = m.nombre;
    document.getElementById("pdf_especie").innerText = m.especie;
    document.getElementById("pdf_raza").innerText = m.raza;
    document.getElementById("pdf_peso").innerText = m.peso_actual + " Kg";
    document.getElementById("pdf_edad").innerText = m.edad;
    document.getElementById("pdf_color").innerText = m.color_senas;
    document.getElementById("pdf_responsable").innerText = m.nombre_responsable;
    document.getElementById("pdf_telefono").innerText = m.telefono_emergencia;

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function generarPDFDesdeVista() {
    const data = {
        nombre: document.getElementById("pdf_nombre").innerText,
        especie: document.getElementById("pdf_especie").innerText,
        raza: document.getElementById("pdf_raza").innerText,
        peso_actual: document.getElementById("pdf_peso").innerText,
        edad: document.getElementById("pdf_edad").innerText,
        color_senas: document.getElementById("pdf_color").innerText,
        nombre_responsable: document.getElementById("pdf_responsable").innerText,
        telefono_emergencia: document.getElementById("pdf_telefono").innerText
    };
    descargarPDFDirecto(data);
}

function descargarPDFDirecto(m) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text("Ficha Digital - Santuario de Mascotas", 14, 20);

    doc.setFontSize(10);
    doc.text("Expediente de Registro Oficial", 14, 26);
    doc.line(14, 29, 196, 29);

    doc.setFontSize(12);
    doc.text(`1. Nombre: ${m.nombre}`, 14, 40);
    doc.text(`2. Especie: ${m.especie}`, 14, 48);
    doc.text(`3. Raza: ${m.raza}`, 14, 56);
    doc.text(`4. Edad: ${m.edad} años`, 14, 64);
    doc.text(`5. Peso Actual: ${m.peso_actual}`, 14, 72);
    doc.text(`6. Señas/Color: ${m.color_senas}`, 14, 80);
    doc.text(`7. Responsable: ${m.nombre_responsable}`, 14, 88);
    doc.text(`8. Teléfono Emergencia: ${m.telefono_emergencia}`, 14, 96);

    doc.line(14, 102, 196, 102);

    doc.save(`Ficha_${m.nombre}_Santuario.pdf`);
}
</script>

</body>
</html>