<?php
class ConexionBD {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $bd = "santuario_mascotas";
    protected $conn;

    public function __construct() {
        try {
            
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->bd};charset=utf8", $this->usuario, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            
            die("<div class='w3-panel w3-red w3-round'><b>Error de Conexión:</b> " . htmlspecialchars($e->getMessage()) . "</div>");
        }
    }
}
?>