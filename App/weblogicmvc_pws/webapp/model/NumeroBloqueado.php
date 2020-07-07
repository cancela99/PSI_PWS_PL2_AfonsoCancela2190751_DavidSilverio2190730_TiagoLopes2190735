<?php

use ArmoredCore\WebObjects\Session;

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
        //$_SESSION['sum'] = $somaLocal;
        Session::set('sum', $somaLocal);

        if($somaLocal != $somaDados) {
            if($somaLocal > $somaDados) {
                //$_SESSION['sum'] = 0;
                //$_SESSION['local'] = null;
                Session::set('sum', 0);
                Session::set('local', null);

            }
            $flag = false;
        } else if($somaLocal == $somaDados){
            if (!isset($_SESSION['numBloq'])) {
                //$_SESSION['numBloq'] = [];
            }
            foreach ($numArray as $num) {
                //array_push($_SESSION['numBloq'], $num);
                $numAr[] = $num;
                $_SESSION['numBloq'][] = $num;
                //Session::set('numBloq', $numAr);

            }

            Session::set('controlDiceRoll', null);

            $this->numerosBloqueados = $_SESSION['numBloq'];
            $flag = true;

        }

        return $flag;
    }

    public function checkFinalJogada($numArray, $diceSum) {

        $flag = false;
        $local = [];
        $sum = 0;

        for($i = 0; $i < count($numArray); $i++) {
            if($numArray[$i] == $diceSum) {
                //$local = $numArray[$i];
                array_push($local, $numArray[$i]);
            } else {
                for($j = 0; $j < count($numArray); $j++) {
                    if($numArray[$i] != $numArray[$j]) {
                        $sum = $sum + $numArray[$j];

                        if (($numArray[$i] + $sum) == $diceSum) {
                            //$local = $sum;
                            array_push($local, $sum);
                        } else {
                            Session::set('falseResults', $sum);
                        }
                    }
                }
            }
        }

        for($i = 0; $i < count($local); $i++) {
            if($local[$i] == $diceSum) {
                $flag = true;
            }
        }

        return $flag;

        /*
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

        $local = array_filter($local, function($v) use ($diceSum) {
            return(array_sum($v) == $diceSum);
        });

        return $local; */
    }

    public function getFinalPointsSum() {
        return Session::get('sessionPoints');
    }

}