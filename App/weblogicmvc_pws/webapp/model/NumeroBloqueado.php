<?php


class NumeroBloqueado
{
    public $numerosBloqueados = [];

    public function iniciar() {
        if(count($this->numerosBloqueados) == 0) {
            $this->numerosBloqueados = [];
        }
    }

    public function bloquearNumero($numArray, $somaDados) {
        $flag = true;

        $somaLocal = array_sum($numArray);
        $_SESSION['sum'] = $somaLocal;

        if($somaLocal != $somaDados) {
            if($somaLocal > $somaDados) {
                $_SESSION['sum'] = 0;
                $_SESSION['local'] = null;
            }
            $flag = false;
        } else if($somaLocal == $somaDados){
            if (!isset($_SESSION['numBloq'])) {
                //$_SESSION['numBloq'] = [];
            }
            foreach ($numArray as $num) {
                //array_push($_SESSION['numBloq'], $num);
                $_SESSION['numBloq'][] = $num;
            }

            $_SESSION['controlDiceRoll'] = null;

            $this->numerosBloqueados = $_SESSION['numBloq'];
            $flag = true;

        }

        return $flag;
    }

    public function checkFinalJogada($numArray, $diceSum) {
        $flag = false;

        for($i = 0; $i < count($numArray); $i++) {
            if($numArray[$i] == $diceSum) {
                $flag = true;
                break;
            }
            for($j = $i; $j < count($numArray); $j++) {
                if($numArray[$i] != $numArray[$j]) {
                    if (($numArray[$i] + $numArray[$j]) == $diceSum) {
                        $_SESSION['TRUE RESULTS -> '][] = ($numArray[$i] + $numArray[$j]) . " | " .$numArray[$i] ."+".$numArray[$j];
                        $flag = true;
                        break;
                    } else {
                        $_SESSION['FALSE RESULTS -> '][] = ($numArray[$i] + $numArray[$j]) . " | " .$numArray[$i] ."+".$numArray[$j];
                        $flag = false;
                        break;
                    }
                }
            }
        }

        return $flag;

        /*$local = array();

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

        return $local;*/
    }

    public function getFinalPointsSum() {
        return $_SESSION['sessionPoints'];
    }

}