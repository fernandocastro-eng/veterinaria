<?php
class Paginacion {
    private $totalRegistros;
    private $registrosPorPagina;
    private $paginaActual;

    public function __construct($totalRegistros, $registrosPorPagina = 5) {
        $this->totalRegistros = $totalRegistros;
        $this->registrosPorPagina = $registrosPorPagina;
        $this->paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($this->paginaActual < 1) {
            $this->paginaActual = 1;
        }
    }

    public function getInicio() {
        return ($this->paginaActual - 1) * $this->registrosPorPagina;
    }

    public function getLimite() {
        return $this->registrosPorPagina;
    }

    public function getTotalPaginas() {
        return ceil($this->totalRegistros / $this->registrosPorPagina);
    }

    public function renderizarBotones() {
        $totalPaginas = $this->getTotalPaginas();
        if ($totalPaginas <= 1) return;

        echo '<div class="w3-center w3-margin-top">';
        echo '<div class="w3-bar">';

        // Botón Anterior
        if ($this->paginaActual == 1) {
            echo '<a href="#" class="w3-bar-item w3-button w3-border w3-teal w3-disabled">&laquo;</a>';
        } else {
            echo '<a href="?pagina=' . ($this->paginaActual - 1) . '" class="w3-bar-item w3-button w3-border w3-teal">&laquo;</a>';
        }

        // Botones Numerados
        for ($i = 1; $i <= $totalPaginas; $i++) {
            if ($i == $this->paginaActual) {
                echo '<a href="?pagina=' . $i . '" class="w3-bar-item w3-button w3-border w3-dark-grey">' . $i . '</a>';
            } else {
                echo '<a href="?pagina=' . $i . '" class="w3-bar-item w3-button w3-border">' . $i . '</a>';
            }
        }

        // Botón Siguiente
        if ($this->paginaActual >= $totalPaginas) {
            echo '<a href="#" class="w3-bar-item w3-button w3-border w3-teal w3-disabled">&raquo;</a>';
        } else {
            echo '<a href="?pagina=' . ($this->paginaActual + 1) . '" class="w3-bar-item w3-button w3-border w3-teal">&raquo;</a>';
        }

        echo '</div>';
        echo '</div>';
    }
}
?>