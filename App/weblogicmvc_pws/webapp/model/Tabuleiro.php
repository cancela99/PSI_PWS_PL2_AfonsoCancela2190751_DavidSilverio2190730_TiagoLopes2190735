<?php

use ArmoredCore\WebObjects\Session;

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

        \Tracy\Debugger::barDump($_SESSION['numBloq']);

        $this->iniciar();
        $this->numBloqueadosP1 = $this->numerosBloqueados;
        $aux = array_diff($arrayInteiro, $_SESSION['numBloq']);
        $unblockedGates = array_values($aux);
        $_SESSION['sessionPoints'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function checkFinalJogadaP2($soma) {
        $arrayInteiro = array(1,2,3,4,5,6,7,8,9);

        $this->iniciar();
        $this->numBloqueadosP2 = $this->numerosBloqueados;
        $aux = array_diff($arrayInteiro, $_SESSION['numBloq']);
        $unblockedGates = array_values($aux);
        $_SESSION['sessionPoints'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function getVencedor() {
        //relizar a soma dos numeros livres de ambos os players
       /* $sumBloqP1 = array_sum($this->numBloqueadosP1);

        $sumBloqP2 = array_sum($this->numBloqueadosP2);

        //se o a soma dos dados do P1 < P2 então P1 ganha, senão ganha P2
        if($sumBloqP1 < $sumBloqP1){
            //P1 VENCEU
        } elseif($sumBloqP1 == $sumBloqP2) {
            // EMPATE
        } else {
            // P2 VENCEU
        }

        //retornar o player vencedor*/
    }

    public function getPointsVencedor() {
        //relizar a soma dos numeros livres de ambos os players
        //$sumBloqP1 = array_sum($this->numBloqueadosP1);

        //$sumBloqP2 = array_sum($this->numBloqueadosP2);


        return $_SESSION['sessionPoints'];
    }
}
