<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

class GameController extends BaseController
{
    public function lancarDado() {

        $dados = new Dado();
        $valorDados = array();

        $resultado1 = 'dado'.$dados->mostrarDados().'.png';
        $resultado2 = 'dado'.$dados->mostrarDados().'.png';


        $valorDados = array($resultado1,$resultado2);



        return View::make('stbox/game', ['valorDados'=>$valorDados]);



    }
}