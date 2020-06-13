<?php


class Tabuleiro
{
    private $dado;
    public $valorDado1;
    public $valorDado2;
    public $numBloqueadosP1;
    public $numBloqueadosP2;

    public function lancarDados() {
        $this->dado = new Dado();

        $valorDado1 = $this->dado->lancarDado();
        $valorDado2 = $this->dado->lancarDado();
    }

    public function checkFinalJogadaP1($soma) {

    }

    public function checkFinalJogadaP2($soma) {

    }

    public function getVencedor() {

    }

    public function getPointsVencedor() {

    }
}
