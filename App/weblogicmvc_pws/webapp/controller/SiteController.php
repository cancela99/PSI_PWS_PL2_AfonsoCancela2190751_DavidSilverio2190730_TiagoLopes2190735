<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\Session;
use ArmoredCore\WebObjects\View;

class SiteController extends BaseController
{

    //Função que mostra a vista das regras
    public function Rules(){
        return View::make('stbox.rules');
    }

    //Função que mostra a vista de login
    public function Login() {
        return View::make('stbox.login');
    }

    //Faz uma query à base de dados para ir buscar o Top 10 e devolve um array com o Top 10
    public function Top10() {
        //Finder dinâmico para filtrar os dados que queremos da BD
        $top10 = Match::find('all',array('conditions'=> array('vencedor = ?','G'), 'order' => 'pontuacao desc', 'limit' => 10));

        //Verifica se o finder encontrou alguma partida
        if($top10 == null){
            $erro = 'O site não tem partidas concluídas';
            return View::make('stbox.top10' , ['top10'=>$top10, 'top10erro'=>$erro]);
        }else{
            //Senão devolve a vista com aviso
            return View::make('stbox.top10', ['top10'=>$top10]);
        }
    }

    //Função que mostra a vista para fazer o registo
    public function Register() {
        return View::make('stbox.register');
    }

    //Função que mostra a vista da página inicial
    public function Home() {
        return View::make('stbox.homepage');
    }

    //Função que devolve a vista de jogo
    public function Game() {
        //Verifica se o utilizador tem login feito, se tiver devolve a vista do jogo
        if(Session::has('userData')){
            return View::make('stbox.gamepage', ["valorDado" => array(6, 6), "numArray" => array()]);
        }else{
            //Senão a função devolve a vista de login com um aviso
            Session::set('notLoggedIn','Faça login para jogar');
            return View::make('stbox.login');
        }
    }


    //Faz uma query à BD para ir buscar os valores e devolve os dados por um array
    public function Matches($page){

        //Verifica se o utilizador tem login feito
        if(Session::has('userData')){

            if($page != null){
                $paginaAtual = $page;
            } else {
                $paginaAtual = 1;
            }

            $partidas_por_pagina = 5;

            //descobre o offset para cada página de acordo com o nº de partidas p/ página
            $offset = ($paginaAtual - 1) * $partidas_por_pagina;

            $user = Session::get('userData');
            //Finder dinâmico para filtrar os dados da BD
            $matches = Match::find('all', array('conditions' => array('user_id = ?', $user->id)));

            $totalPartidas = count($matches);

            //retorna o valor arredondado mais alto do nº de páginas
            $totalPaginas = ceil($totalPartidas / $partidas_por_pagina);


            //Verifica se existem partidas
            //Verifica se o finder encontrou alguma partida
            if($matches != null){
                //extrai do array matches as partidas de acordo com o offset
                $partidas = array_slice($matches, $offset, $partidas_por_pagina, true);
                if($page > $totalPaginas){
                    Redirect::toRoute('stbox/matches', $totalPaginas);
                } else if ($page < 1){
                    Redirect::toRoute('stbox/matches', 1);
                } else {
                    View::make('stbox.matches', ['matches' => $partidas] + ['pages' => $totalPaginas] + ['paginaAtual' => $paginaAtual]);
                }
            } else {
                //Senão devolve a vista com um aviso
                $aviso = 'Sem partidas realizadas';
                View::make('stbox.matches', ['matches' => null, 'matchesWarning' => $aviso]);
            }
        }else{
            //Senão devolve uma mensagem de erro
            Session::set('notLoggedIn','É necessário realizar login');
            return View::make('stbox.errorNotLoggedIn');
        }
    }

    //Função que devolve uma vista de erro
    public function Erro(){
        return View::make('stbox.errorNotLoggedIn');
    }


    //Função que devolve a vista do perfil
    public function Profile(){
        //Verifica se o utilizador fez login, se tiver feito login devolve a vista de perfil com os dados de utilizador
        if(Session::has('userData')){
            $users = new User();
            return View::make('stbox.profile', ['users'=>$users]);
        }else{
            //Senão a função devolve uma vista de erro com um aviso
            Session::set('notLoggedIn','É necessário realizar login');
            return View::make('stbox.errorNotLoggedIn');
        }
    }
}
