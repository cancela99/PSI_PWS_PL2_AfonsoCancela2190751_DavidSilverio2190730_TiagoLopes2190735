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

        $aux = array_diff($arrayInteiro, $_SESSION['numBloq']);
        $unblockedGates = array_values($aux);
        $_SESSION['sessionPoints'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function checkFinalJogadaP2($soma) {
        $arrayInteiro = array(1,2,3,4,5,6,7,8,9);

        $aux = array_diff($arrayInteiro, $_SESSION['numBloq']);
        $unblockedGates = array_values($aux);
        $_SESSION['sessionPoints'] = array_sum($unblockedGates);

        return $this->checkFinalJogada($unblockedGates, $soma);
    }

    public function getVencedor() {
        // Vai buscar vencedor -> quem tem maior soma de numeros bloqueados
        $vencedor = 0;
        $p1Soma = array_sum($this->numBloqueadosP1);
        $p2Soma = array_sum($this->numBloqueadosP2);

        if($p1Soma > $p2Soma) {
            $vencedor = 1;
        } else if($p1Soma < $p2Soma) {
            $vencedor = 2;
        } else {
            $vencedor = 0;
        }

        return $vencedor;
    }

    public function getPointsVencedor() {
        // vai buscar diferenca de pontos pela qual foi vencedor
        $pontosVencedor = 0;
        $p1Soma = array_sum($this->numBloqueadosP1);
        $p2Soma = array_sum($this->numBloqueadosP2);

        if($p1Soma > $p2Soma) {
            $pontosVencedor = $p1Soma - $p2Soma;
        } else if($p1Soma < $p2Soma) {
            $pontosVencedor = $p2Soma - $p1Soma;
        } else {
            $pontosVencedor = 0;
        }

        return $pontosVencedor;
    }
}
