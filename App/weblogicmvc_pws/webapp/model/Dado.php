<?php


use ArmoredCore\WebObjects\Asset;

class Dado
{

    public function mostrarDados(){

        $min = 1;
        $max = 6;

        $resultado = rand($min,$max);


        return $resultado;
    }

}