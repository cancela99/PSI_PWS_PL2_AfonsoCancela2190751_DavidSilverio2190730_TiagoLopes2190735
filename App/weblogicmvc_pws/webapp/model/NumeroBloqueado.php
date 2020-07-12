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
            if(Session::get('numBloq') == null) {
                Session::set('numBloq', []);
            }

            //Faz o merge de todos os numeros bloqueados com os numeros bloqueados na jogada atual
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

        \Tracy\Debugger::barDump($sumResults);
        return $flag;
    }


    public function getFinalPointsSum() {
        return Session::get('sessionPoints');
    }

}