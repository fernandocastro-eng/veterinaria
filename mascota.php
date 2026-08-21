<?php
class Mascota {
    
    protected $nombre;
    protected $especie;
    protected $raza;
    protected $edad;
    protected $pesoActual;
    protected $colorSenas;
    protected $nombreResponsable;
    protected $telefonoEmergencia;

    
    public function __construct($nombre, $especie, $raza, $edad, $pesoActual, $colorSenas, $nombreResponsable, $telefonoEmergencia) {
        $this->nombre = $nombre;
        $this->especie = $especie;
        $this->raza = $raza;
        $this->edad = $edad;
        $this->setPesoActual($pesoActual); 
        $this->colorSenas = $colorSenas;
        $this->nombreResponsable = $nombreResponsable;
        $this->telefonoEmergencia = $telefonoEmergencia;
    }

    
    public function getNombre() { return $this->nombre; }
    public function getEspecie() { return $this->especie; }
    public function getRaza() { return $this->raza; }
    public function getEdad() { return $this->edad; }
    public function getPesoActual() { return $this->pesoActual; }
    public function getColorSenas() { return $this->colorSenas; }
    public function getNombreResponsable() { return $this->nombreResponsable; }
    public function getTelefonoEmergencia() { return $this->telefonoEmergencia; }

    
    public function setPesoActual($peso) {
        
        if (!is_numeric($peso) || $peso <= 0) {
            throw new Exception("El peso ingresado debe ser numérico y estrictamente mayor que cero.");
        }
        $this->pesoActual = $peso;
    }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setEspecie($especie) { $this->especie = $especie; }
    public function setRaza($raza) { $this->raza = $raza; }
    public function setEdad($edad) { $this->edad = $edad; }
    public function setColorSenas($colorSenas) { $this->colorSenas = $colorSenas; }
    public function setNombreResponsable($nombreResponsable) { $this->nombreResponsable = $nombreResponsable; }
    public function setTelefonoEmergencia($telefonoEmergencia) { $this->telefonoEmergencia = $telefonoEmergencia; }
}
?>