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
        Session::set('sum', $somaLocal);

        if($somaLocal != $somaDados) {
            if($somaLocal > $somaDados) {
                Session::set('sum', 0);
                Session::set('local', null);
            }
            $flag = false;
        } else if($somaLocal == $somaDados){
            //!Session::has('numBloq')
            if(Session::get('numBloq') == null) {
                Session::set('numBloq', []);
            }

            $jogadaAnterior = Session::get('numBloq');

            $jogadaAtual = $numArray;

            $mergeNumBloq = array_merge($jogadaAnterior, $jogadaAtual);

            Session::set('numBloq', $mergeNumBloq);

            Session::set('controlDiceRoll', null);

            $this->numerosBloqueados = Session::get('numBloq');
            $flag = true;

        }

        return $flag;
    }

    public function checkFinalJogada($numArray, $diceSum) {

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

        $local = array_filter($local, function($v) use ($diceSum) {
            return(array_sum($v) == $diceSum);
        });

        return $local;*/



        //For loop a percorrer o array dos numeros livres e a somar, de forma a retornar o resultado de todas as possíveis somas

        //array que vai receber todas as possibilidades de soma
        $sumResults = [];

        $flag = null;

        foreach ($numArray as $livre) {
            if ($livre == $diceSum) {
                $flag = true;
                break;
            }
        }

        if($flag != true) {
            foreach ($numArray as $i){
                foreach ($numArray as $j){
                    foreach ($numArray as $h){
                        foreach ($numArray as $k){
                            if($i != $j && $j != $h && $h != $k && $i != $h && $i != $k && $j != $k) {
                                $soma = ($i + $j + $h + $k);
                                if($soma == $diceSum){
                                    $flag = true;
                                    array_push($sumResults, $soma);
                                }
                            }
                        }
                    }
                }
            }

            foreach ($numArray as $i){
                foreach ($numArray as $j){
                    foreach ($numArray as $h){
                        if($i != $j && $j != $h && $i != $h){
                            $soma = ($i + $j + $h);
                            if($soma == $diceSum){
                                $flag = true;
                                array_push($sumResults, $soma);
                            }
                        }
                    }
                }
            }

            foreach ($numArray as $i){
                foreach ($numArray as $j){
                    if($i != $j){
                        $soma = ($i +$j);
                        if($soma == $diceSum){
                            $flag = true;
                            array_push($sumResults, $soma);
                        }
                    }
                }
            }
        }

        //procurar no array de somas se alguma soma == somaDados
        /*if(in_array($diceSum, $sumResults)){
            $flag = true;
        } else {
            $flag = false;
        }*/

        \Tracy\Debugger::barDump($sumResults, "Total de somas");
        return $flag;
    }


    public function getFinalPointsSum() {
        return Session::get('sessionPoints');
    }

}