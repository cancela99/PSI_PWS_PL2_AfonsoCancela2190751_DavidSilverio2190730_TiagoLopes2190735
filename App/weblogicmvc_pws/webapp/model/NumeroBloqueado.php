<?php


class NumeroBloqueado
{
    public $numerosBloqueados;

    public function iniciar() {
        $this->numerosBloqueados = [];
    }

    public function bloquearNumero($numArray, $somaDados) {
        $flag = 0;
        $local = [];

        foreach($numArray as $num) {
            if($somaDados == $num) {
                array_push($this->numerosBloqueados, $somaDados);
                if(($key = array_search($num, $numArray)) !== false)
                    array_push($local, $numArray[$key]);
                $flag = 1;
            }
        }
        if($flag != 1) {
            // Implementar algoritmo que dado um Array de tamanho N e um inteiro K,
            // descobre todas as combinações únicas no array cuja soma seja igual a K.
        }

        return $local;
    }

    public function checkFinalJogada($numArray, $somaDados) {

    }
    public function getFinalPointsSum() {

    }

}