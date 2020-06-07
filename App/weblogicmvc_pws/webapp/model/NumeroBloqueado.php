<?php


class NumeroBloqueado
{
    public $numerosBloqueados;

    public function iniciar() {
        $this->numerosBloqueados = [];
    }

    public function bloquearNumero($numArray, $somaDados) {
        $flag = 0;
        $soma = 0;

        foreach($numArray as $num) {
            if($somaDados == $num) {
                array_push($this->numerosBloqueados, $somaDados);
                if(($key = array_search($num, $numArray)) !== false)
                    unset($numArray[$key]);
                $flag = 1;
            }
        }
        if($flag != 1) {
            for($i = 0; $i<count($numArray)+1; $i++) {
                $soma += $numArray[$i];
                $soma < $somaDados ? $r = "r" : $s = "s";
            }
        }
    }

    public function checkFinalJogada($numArray, $somaDados) {

    }
    public function getFinalPointsSum() {

    }

}