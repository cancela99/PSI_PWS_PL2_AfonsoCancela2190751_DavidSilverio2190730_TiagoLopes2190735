<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

class GameController extends BaseController
{
    public function iniciarJogo() {
        $gameEngine = new GameEngine();
        $gameEngine->iniciarJogo();

        $status = "enabled";
        return View::make('stbox.gamepage', ['valorDado' => array(6, 6), 'numArray' => array(), 'status' => $status]);
    }

    public function bloquearNumero() {
        $flag=0;
        $sum = 0;
        $local = [];
        $numero = 0;

        if (isset($_POST['portoes'])) {
            $flag = 1;
        }
        // Operador ternário que realiza a verificação de dados no POST['portoes'];
        $flag == 1 ? $numero = $_POST['portoes'] : $numero = 0;

        //array_push($local, $numero);
        //array_unique($local);
        if(!isset($_SESSION['local'])) {
            $_SESSION['local'] = [];
        }

        array_push($_SESSION['local'], (int)$numero);
        array_unique($_SESSION['local']);
        for($i = 0; $i < count($_SESSION['local']); $i++) {
            // verificar valores 1 a 1 para remover repetidos.
        }

        $_SESSION['sum'] = array_sum($_SESSION['local']);
        //$_SESSION['local'] = null;
        //$_SESSION['sum'] = null;

        return View::make('stbox.gamepage', ['valorDado' => array(6, 6), 'numArray' => array(), 'status' => $numero, 'statuss' => $_SESSION['local']]);
    }

    public function mostrarDado() {

        $dados = new Dado();

        $resultado1 = $dados->lancarDado();
        $resultado2 = $dados->lancarDado();

        $valorDado = array($resultado1, $resultado2);

        $somaDados = $resultado1 + $resultado2;
        $freeGate =  array(1,2,3,4,5,6,7,8,9);//$_POST['freeGate'];
        $numArray = $this->mostrarNumerosBloqueados($freeGate, $somaDados);

        return View::make('stbox.gamepage', ['valorDado' => $valorDado, 'numArray' => $numArray]);
    }

    public function mostrarNumerosBloqueados($freeGate, $somaDados) {

        $numerosBloqueados = new NumeroBloqueado();
        $numerosBloqueados->iniciar();

        $numArray = $numerosBloqueados->bloquearNumero($freeGate, $somaDados);

        return $numArray;
    }
}