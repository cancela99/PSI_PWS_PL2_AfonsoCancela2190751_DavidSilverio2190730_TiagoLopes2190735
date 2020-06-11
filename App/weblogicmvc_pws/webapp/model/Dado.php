<?php

class Dado
{
    public function lancarDado() {

        $min = 1;
        $max = 6;

        $resultado = rand($min,$max);

        return $resultado;
    }
}