<?php

class Dado
{
    //Função que gera o número do dado
    public function lancarDado() {

        $min = 1;
        $max = 6;

        $resultado = rand($min,$max);

        return $resultado;
    }
}