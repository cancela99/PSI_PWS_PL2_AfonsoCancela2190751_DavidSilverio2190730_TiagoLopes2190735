<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Session;

class GameController extends BaseController
{

    public function iniciarJogo() {
        $this->clear();

        $gameEngine = new GameEngine();
        $gameEngine->iniciarJogo();

        //$_SESSION['gameEngine'] = $gameEngine;
        Session::set('gameEngine', $gameEngine);
        $_SESSION['controlDiceRoll'] = null;
        //$_SESSION['disableSegur'] = "disable";
        Session::set('disableSegur', 'disable');

        if($gameEngine->getEstadoJogo()) {
            $status = "enabled";
        } else {
            $status = "disabled";
        }

        if($gameEngine->getEstadoJogo() == 1){
            Session::set('playerColour', "#00D3B6");
        } else if($gameEngine->getEstadoJogo() == 2) {
            Session::set('playerColour', "#E0D600");
        }

        return View::make('stbox.gamepage', ['valorDado' => array(6, 6), 'status' => $status, 'clickedGate' => $_SESSION, "statusGate" => "disabled"]);
    }

    public function nextPlayerTurn() {
        $this->clear();
        $gameEngine = Session::get('gameEngine');

        $_SESSION['controlDiceRoll'] = null;
        //$_SESSION['disableSegur'] = "disable";
        Session::set('disableSegur', 'disable');

        if($gameEngine->getEstadoJogo() == 1){
            Session::set('playerColour', "#00D3B6");
        } else if($gameEngine->getEstadoJogo() == 2) {
            Session::set('playerColour', "#E0D600");
        }
    }

    public function bloquearNumero() {
        $flag=0;
        $numerosBloqueados = new NumeroBloqueado();
        $numerosBloqueados->iniciar();

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
            //$clickedGate = (int)$numero;
            //$_SESSION['idClickedGate'] = $clickedGate;
        }

        $isTrue = $numerosBloqueados->bloquearNumero($_SESSION['local'], $_SESSION['somaDados']);

        if($isTrue == true) {
            // Os numeros foram bloqueados e adicionados ao array de numerosBloqueados.
            $gameEngine = Session::get('gameEngine');
            $tabuleiro = $gameEngine->tabuleiro;
            $estadoAtual = $gameEngine->getEstadoJogo();

            if($estadoAtual == 1) {
                //$tabuleiro->numBloqueadosP1 = [];
                $tabuleiro->numBloqueadosP1 = $numerosBloqueados->numerosBloqueados;
            } else if($estadoAtual == 2) {
                //$tabuleiro->numBloqueadosP2 = [];
                $tabuleiro->numBloqueadosP2 = $numerosBloqueados->numerosBloqueados;
            }
            //$_SESSION['numerosBloqueados'] = $numerosBloqueados->numerosBloqueados;
            Session::set('gameEngine', $gameEngine);
            \Tracy\Debugger::barDump($gameEngine, "Game Engine");
        }
        $_SESSION['primeiraJogada'] = true;


        return View::make('stbox.gamepage', ['valorDado' => $_SESSION['valorDado'], 'status' => "enabled", 'clickedGate' => $_SESSION, "statusGate" => "enabled"]);
    }

    public function clear() {
        $_SESSION['local'] = null;
        $_SESSION['sum'] = null;
        $_SESSION['valorDado'] = null;
        $_SESSION['somaDados'] = null;
        $_SESSION['numerosBloqueados'] = null;
        $_SESSION['checkFinal'] = null;
        $_SESSION['primeiraJogada'] = null;
        $_SESSION['numBloq'] = null;
        $_SESSION['FLAG'] = null;
    }

    public function mostrarDado() {

        $gameEngine = Session::get('gameEngine');
        \Tracy\Debugger::barDump($gameEngine, "Game Engine - Lançar Dados");

        $estadoAtual = $gameEngine->getEstadoJogo();
        $tabuleiro = $gameEngine->tabuleiro;

        $tabuleiro->lancarDados();

        $resultado1 = $tabuleiro->valorDado1;
        $resultado2 = $tabuleiro->valorDado2;

        $valorDado = array($resultado1, $resultado2);

        Session::set('valorDado', $valorDado);
        Session::set('somaDados', $resultado1 + $resultado2);
        Session::set('controlDiceRoll', 'Fim de turno');
        Session::set('disableSegur', 'enable');

        if (isset($_SESSION['primeiraJogada'])) {
            if ($estadoAtual == 1) {
                $checkFinal = $tabuleiro->checkFinalJogadaP1($_SESSION['somaDados']);
                //$_SESSION['checkFinal'] = $checkFinal;
                Session::set('checkFinal', $checkFinal);
                if($checkFinal != true) {
                    Session::set('gameEngine', $gameEngine);
                    $gameEngine->updateEstadoJogo();
                    $this->nextPlayerTurn();
                }

                Session::set('gameEngine', $gameEngine);
                $_SESSION['local'] = null;
                $_SESSION['sum'] = null;

            } else if($estadoAtual == 2) {
                $checkFinal = $tabuleiro->checkFinalJogadaP2($_SESSION['somaDados']);
                //$_SESSION['checkFinal'] = $checkFinal;
                Session::set('checkFinal', $checkFinal);
                if($checkFinal != true) {
                    Session::set('gameEngine', $gameEngine);
                    $gameEngine->updateEstadoJogo();
                    $vencedor = $tabuleiro->getVencedor();
                    $points = $tabuleiro->getPointsVencedor();

                    //$this->insertDataBD($points);

                    if($vencedor == 0 && $points == 0) {
                        //$_SESSION['finalJogo'] = 'Jogo terminado! Empate!';
                        Session::set('finalJogo', 'Jogo terminado! Empate!');
                    } else {
                        //$_SESSION['finalJogo'] = 'Jogo terminado! Jogador '.$vencedor.' ganhou por '.$points.' pontos.';
                        Session::set('finalJogo', 'Jogo terminado! Jogador '.$vencedor.' ganhou por '.$points.' pontos.');
                    }

                    $this->iniciarJogo();

                }

                Session::set('gameEngine', $gameEngine);
                $_SESSION['local'] = null;
                $_SESSION['sum'] = null;
            }
        }

        return View::make('stbox.gamepage', ['valorDado' => $valorDado, 'status' => 'enabled', 'clickedGate' => $_SESSION, "statusGate" => "enabled"]);
    }

    public function insertDataBD($points) {
        $partida = new Match();
        $partida->pontuacao = $points;
        if($points == 0) {
            $partida->vencedor = 'G';
        } else {
            $partida->vencedor = 'P';
        }
        $userData = Session::get('userData');
        $partida->idusername = $userData->id;

        $partida->save();
    }
}