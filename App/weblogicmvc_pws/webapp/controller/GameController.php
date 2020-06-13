<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

class GameController extends BaseController
{

    public function iniciarJogo() {
        $gameEngine = new GameEngine();
        $gameEngine->iniciarJogo();

        if($gameEngine->getEstadoJogo()) {
            $status = "enabled";
        } else {
            $status = "disabled";
        }

        return View::make('stbox.gamepage', ['valorDado' => array(6, 6), 'status' => $status]);
    }

    public function bloquearNumero() {
        $flag=0;

        if (isset($_POST['portoes'])) {
            $flag = 1;
        }

        // Operador ternário que realiza a verificação de dados no POST['portoes'];
        $flag == 1 ? $numero = $_POST['portoes'] : $numero = 0;

        if(!isset($_SESSION['local'])) {
            $_SESSION['local'] = [];
        }

        if(array_search((int)$numero, $_SESSION['local']) == false) {
            array_push($_SESSION['local'], (int)$numero);
        }

        $_SESSION['sum'] = array_sum($_SESSION['local']);
        //$_SESSION['local'] = null;
        //$_SESSION['sum'] = null;

        return View::make('stbox.gamepage', ['valorDado' => array(6, 6)]);
    }

    public function mostrarDado() {

        $tabuleiro = new Tabuleiro();
        $tabuleiro->lancarDados();

        $resultado1 = $tabuleiro->valorDado1;
        $resultado2 = $tabuleiro->valorDado2;

        $valorDado = array($resultado1, $resultado2);

        $_SESSION['somaDados'] = $_SESSION['valorDado1'] + $_SESSION['valorDado2'];

        return View::make('stbox.gamepage', ['valorDado' => $valorDado, 'status' => "enabled"]);
    }

    public function mostrarNumerosBloqueados($freeGate, $somaDados) {

        $numerosBloqueados = new NumeroBloqueado();
        $numerosBloqueados->iniciar();

        $numArray = $numerosBloqueados->bloquearNumero($freeGate, $somaDados);

        return $numArray;
    }
}