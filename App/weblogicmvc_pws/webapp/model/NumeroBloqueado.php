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

    public function checkFinalJogada($numArray, $somaDados) {
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

    public function getFinalPointsSum() {
        return $_SESSION['points'];
    }

}





/* Função bloquearNumero.
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
*/

/*
            switch($somaDados) {
                case 2:
                    $flag = ($numArray === array(2));
                    break;
                case 3:
                    $flag = ($numArray === array(3) || $numArray === array(1, 2));
                    break;
                case 4:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
                case 5:
                    $flag = ($numArray === array(5) || $numArray === array(1, 4) || $numArray === array(2, 3));
                    break;
                case 6:
                    $flag = ($numArray === array(6) || $numArray === array(1, 5) || $numArray === array(2, 4) || $numArray === array(1, 2, 3));
                    break;
                case 7:
                    $flag = ($numArray === array(7) || $numArray === array(2, 5) || $numArray === array(1, 6) || $numArray === array(4, 3) || $numArray === array(1, 2, 4));
                    break;
                case 8:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
                case 9:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
                case 10:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
                case 11:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
                case 12:
                    $flag = ($numArray === array(4) || $numArray === array(1, 3));
                    break;
            }

for($i = 0; $i < count($numArray); $i++) {
            for($j = 0; $j < count($numArray); $j++) {
                for($k = 0; $k < count($numArray); $k++) {
                    for($h = 0; $h < count($numArray); $h++) {
                        if($i != $j && $i != $k && $i != $h && $j != $k && $j != $h && $k != $h) {
                            ($numArray[$i] + $numArray[$j] + $numArray[$k] + $numArray[$h]) == $somaDados ||
                            ($numArray[$i] + $numArray[$j] + $numArray[$k]) == $somaDados ||
                            ($numArray[$i] + $numArray[$k] + $numArray[$h]) == $somaDados ||
                            ($numArray[$i] + $numArray[$j] + $numArray[$h]) == $somaDados ||
                            ($numArray[$j] + $numArray[$k] + $numArray[$h]) == $somaDados ||
                            ($numArray[$i] + $numArray[$j]) == $somaDados ||
                            ($numArray[$i] + $numArray[$k]) == $somaDados ||
                            ($numArray[$i] + $numArray[$h]) == $somaDados ||
                            ($numArray[$j] + $numArray[$k]) == $somaDados ||
                            ($numArray[$j] + $numArray[$h]) == $somaDados ||
                            ($numArray[$k] + $numArray[$h]) == $somaDados ||
                            $numArray[$k] == $somaDados ||
                            $numArray[$j] == $somaDados ||
                            $numArray[$i] == $somaDados ||
                            $numArray[$h] == $somaDados
                            ? $flag = true : $flag = false;
                        }
                    }
                }
            }
        }

        if($flag == true) {
            $_SESSION['controlDiceRoll'] = "Fim de turno";
            $_SESSION['FLAG'] = true;
        } else {
            $_SESSION['FLAG'] = false;
        }



*/