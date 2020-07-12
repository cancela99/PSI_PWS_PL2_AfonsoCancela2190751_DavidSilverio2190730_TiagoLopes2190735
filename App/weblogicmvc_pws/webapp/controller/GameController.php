<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;
use ArmoredCore\WebObjects\Session;

class GameController extends BaseController
{

    public function iniciarJogo() {

        if(Session::has('userData')){
            $this->clear();


            $gameEngine = new GameEngine();

            if($gameEngine->getEstadoJogo() == 0) {
                $gameEngine->iniciarJogo();

                Session::set('gameEngine', $gameEngine);
                Session::set('controlDiceRoll', null);
                Session::set('disableSegur', "disable");
            }

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
        }else{
            Session::set('notLoggedIn','Faça login para jogar');
            return View::make('stbox.login');
        }

    }

    public function nextPlayerTurn() {
        $this->clear();
        $gameEngine = Session::get('gameEngine');

        $_SESSION['controlDiceRoll'] = null;
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

        if (Post::has('portoes')) {
            $flag = 1;
        }

        // Operador ternário que realiza a verificação de dados no POST['portoes'];
        $flag == 1 ? $numero = Post::get('portoes') : $numero = 0;

        if(Session::get('local') == null) {
            Session::set('local', []);
        }

        if(array_search((int)$numero, $_SESSION['local']) == false) {
            array_push($_SESSION['local'], (int)$numero);
        }

        $isTrue = $numerosBloqueados->bloquearNumero($_SESSION['local'], $_SESSION['somaDados']);

        if($isTrue == true) {
            // Os numeros foram bloqueados e adicionados ao array de numerosBloqueados.
            $gameEngine = Session::get('gameEngine');
            $tabuleiro = $gameEngine->tabuleiro;
            $estadoAtual = $gameEngine->getEstadoJogo();

            if($estadoAtual == 1) {
                $tabuleiro->numBloqueadosP1 = $numerosBloqueados->numerosBloqueados;
            } else if($estadoAtual == 2) {
                $tabuleiro->numBloqueadosP2 = $numerosBloqueados->numerosBloqueados;
            }
            Session::set('numerosBloqueados', $numerosBloqueados->numerosBloqueados);
        }
        Session::set('primeiraJogada', true);

        return View::make('stbox.gamepage', ['valorDado' => $_SESSION['valorDado'], 'status' => "enabled", 'clickedGate' => $_SESSION, "statusGate" => "enabled"]);
    }

    public function clear() {

        Session::set('local', null);
        Session::set('sum', null);
        Session::set('valorDado', null);
        Session::set('somaDados', null);
        Session::set('numerosBloqueados', null);
        Session::set('checkFinal', null);
        Session::set('primeiraJogada', null);
        Session::set('numBloq', null);
        Session::set('FLAG', null);

    }

    public function mostrarDado() {

        $gameEngine = Session::get('gameEngine');
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
                Session::set('checkFinal', $checkFinal);
                if($checkFinal != true) {
                    Session::set('gameEngine', $gameEngine);
                    $gameEngine->updateEstadoJogo();
                    $this->nextPlayerTurn();
                }
                Session::set('gameEngine', $gameEngine);
                Session::set('local', null);
                Session::set('sum', null);

            } else if($estadoAtual == 2) {
                $checkFinal = $tabuleiro->checkFinalJogadaP2(Session::get('somaDados'));
                Session::set('checkFinal', $checkFinal);

                if($checkFinal != true) {
                    Session::set('gameEngine', $gameEngine);
                    $gameEngine->updateEstadoJogo();
                    $vencedor = $tabuleiro->getVencedor();
                    $points = $tabuleiro->getPointsVencedor();

                    $this->insertDataBD($points, $vencedor);

                    if($vencedor == 0 && $points == 0) {
                        Session::set('finalJogo', 'Jogo terminado! Empate!');
                    } else {
                        Session::set('finalJogo', 'Jogo terminado! Jogador '.$vencedor.' ganhou por '.$points.' pontos.');
                    }

                    
                    $this->iniciarJogo();
                }
                Session::set('gameEngine', $gameEngine);
                Session::set('local', null);
                Session::set('sum', null);
            }
        }

        return View::make('stbox.gamepage', ['valorDado' => $valorDado, 'status' => "enabled", 'clickedGate' => $_SESSION, "statusGate" => "enabled"]);
    }

    public function insertDataBD($points, $vencedor) {
        $partida = new Match();
        $partida->pontuacao = $points;

        if($vencedor == 1){
            $partida->vencedor = 'G';
        }else if($vencedor == 0){
            $partida->vencedor = 'E';
        }else{
            $partida->vencedor = 'P';
        }

        $userData = Session::get('userData');
        $partida->user_id = $userData->id;
        if($partida->is_valid()){
            $partida->save();
        }
    }
}