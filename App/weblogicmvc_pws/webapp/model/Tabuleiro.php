<?php


class Tabuleiro extends NumeroBloqueado
{
    private $dado;
    public $valorDado1;
    public $valorDado2;
    public $numBloqueadosP1;
    public $numBloqueadosP2;

    public function lancarDados() {
        $this->dado = new Dado();

        $this->valorDado1 = $this->dado->lancarDado();
        $this->valorDado2 = $this->dado->lancarDado();
    }

    public function checkFinalJogadaP1($soma) {
        $arrayInteiro = array(1,2,3,4,5,6,7,8,9);

        $this->iniciar();
        $this->numBloqueadosP1 = $this->numerosBloqueados;
        $aux = array_diff($arrayInteiro, $_SESSION['numBloq']);
        $unblockedGates = array_values($aux);
        $_SESSION['sessionPoints'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function checkFinalJogadaP2($soma) {
        $this->iniciar();
        $this->numBloqueadosP2 = $this->numerosBloqueados;
        $unblockedGates = array_diff(array(1,2,3,4,5,6,7,8,9), $this->numBloqueadosP2);
        $_SESSION['points'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function getVencedor() {

    }

    public function getPointsVencedor() {
        return $_SESSION['sessionPoints'];
    }
}
