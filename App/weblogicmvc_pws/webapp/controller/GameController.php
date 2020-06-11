<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

class GameController extends BaseController
{
    public function mostrarDado() {

        $dados = new Dado();

        $resultado1 = $dados->lancarDado();
        $resultado2 = $dados->lancarDado();

        $valorDado = array($resultado1, $resultado2);

        $somaDados = $resultado1 + $resultado2;
        $freeGate =  array(1,2,3,4,5,6,7,8,9);//$_POST['freeGate'];
        $numArray = $this->mostrarNumerosBloqueados($freeGate, $somaDados);

        return View::make('stbox.gamepage', ['valorDado' => $valorDado, "numArray" => $numArray]);
    }

    public function mostrarNumerosBloqueados($freeGate, $somaDados) {

        $numerosBloqueados = new NumeroBloqueado();
        $numerosBloqueados->iniciar();

        $numArray = $numerosBloqueados->bloquearNumero($freeGate, $somaDados);

        return $numArray;
    }
}