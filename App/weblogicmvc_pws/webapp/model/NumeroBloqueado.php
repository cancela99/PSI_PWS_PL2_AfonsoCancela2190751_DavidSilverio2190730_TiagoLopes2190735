<?php


class NumeroBloqueado
{
    public $numerosBloqueados;

    public function iniciar() {
        $this->numerosBloqueados = [];
    }

    public function bloquearNumero($numArray, $somaDados) {
        $local = array();

        // Substituir por iteração. 2 For loops, a percorrer o array e a somar.
        function extractList($numArray, &$local, $temp = array()) {
            if (count($temp) > 0 && !in_array($temp, $local))
                $local[] = $temp;
            for($i = 0; $i < count($numArray); $i++) {
                $copy = $numArray;
                $elem = array_splice($copy, $i, 1);
                if (count($copy) > 0) {
                    $add = array_merge($temp, array($elem[0]));
                    sort($add);
                    extractList($copy, $local, $add);
                } else {
                    $add = array_merge($temp, array($elem[0]));
                    sort($add);
                    if (!in_array($temp, $local)) {
                        $local[] = $add;
                    }
                }
            }
        }

        extractList($numArray, $local);

        $local = array_filter($local, function($v) use ($somaDados) {
            return(array_sum($v) == $somaDados);
        });

        return $local;
    }


    public function checkFinalJogada($numArray, $somaDados) {

    }
    public function getFinalPointsSum() {

    }

}