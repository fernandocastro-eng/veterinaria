<?php
require_once "ConexionBD.php";
require_once "Mascota.php";

class MascotaDAO extends ConexionBD {

    // Método para guardar (INSERT)
    public function guardarMascota(Mascota $mascota) {
        try {
            $sql = "INSERT INTO Mascotas (nombre, especie, raza, edad, peso_actual, color_senas, nombre_responsable, telefono_emergencia) 
                    VALUES (:nombre, :especie, :raza, :edad, :peso, :color, :responsable, :telefono)";
            
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':nombre'      => $mascota->getNombre(),
                ':especie'     => $mascota->getEspecie(),
                ':raza'        => $mascota->getRaza(),
                ':edad'        => $mascota->getEdad(),
                ':peso'        => $mascota->getPesoActual(),
                ':color'       => $mascota->getColorSenas(),
                ':responsable' => $mascota->getNombreResponsable(),
                ':telefono'    => $mascota->getTelefonoEmergencia()
            ]);

            return ["exito" => true, "mensaje" => "¡Mascota registrada exitosamente en el Santuario!"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al guardar en la base de datos: " . $e->getMessage()];
        }
    }

    // Método para listar todas las mascotas (SELECT)
    public function obtenerMascotas() {
        try {
            $sql = "SELECT * FROM Mascotas ORDER BY id DESC";
            $stmt =$this->conn->prepare($sql);$stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>