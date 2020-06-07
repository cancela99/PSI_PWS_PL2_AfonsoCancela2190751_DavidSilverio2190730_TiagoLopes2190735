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

        return View::make('stbox.gamepage', ['valorDado' => $valorDado]);
    }

    public function mostrarNumerosBloqueados() {

        $numerosBloqueados = new NumeroBloqueado();
        $numerosBloqueados->iniciar();

        $numerosBloqueados->bloquearNumero(array(2, 3, 5), 5);


    }
}