<?php


use ArmoredCore\Controllers\BaseController;
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

        /*$db = mysqli_connect('localhost', 'root', '', 'shuthebox');

        $query = "SELECT * FROM matches INNER JOIN users ON matches.idUsername = users.id ORDER BY pontuacao ASC LIMIT 10";

        //$query = "SELECT * FROM (SELECT * FROM matches INNER JOIN users ON matches.idUsername = users.id ORDER BY pontuacao ASC LIMIT 10) sub ORDER BY pontuacao DESC";

        $queryResult = mysqli_query($db,$query);

        $match = new Match();

        //Enquanto a query encontrar dados, atribui os dados a um array
        while($match = mysqli_fetch_object($queryResult)){
            $top[] = $match;
        }

        //Se a query não encontrar nenhuns dados que coincidam com a query, a função devolve a vista do Top 10 com um aviso
        if(mysqli_num_rows($queryResult) == 0){
            $_SESSION['noTop'] = 'Não existem partidas concluídas no site';
            return View::make('stbox.top10');
        }else{
            //Senão devolve uma vista do Top 10 com os resultados da query
            return View::make('stbox.top10', ['top10'=>$top]);
        }*/

        $top10 = Match::all();
        $top10Final = [];
        $min = 0;
        $max = 45;
        $j = 0;
        $users = User::all();

        for ($i = $min; $i <= $max; $i++){
            foreach ($top10 as $top){
                if ($j != 10){
                    if($top->pontuacao == $i){
                        $top10Final[$j] = $top;
                        foreach ($users as $user){
                            if($top10Final[$j]->idusername == $user->id){
                                $top10Final[$j]->idusername = $user->username;
                            }
                        }
                        $j++;
                    }
                }
            }
        }

        //$top10Final[0]->idusername = 'tiago';
        \Tracy\Debugger::barDump($top10Final[0]);
        return View::make('stbox.top10', ['top10'=>$top10Final]);
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
        if(/*isset($_SESSION['loggedIn'])*/Session::has('loggedIn')){
            return View::make('stbox.gamepage', ["valorDado" => array(6, 6), "numArray" => array()]);
        }else{
            //Senão a função devolve a vista de login com um aviso
            Session::set('notLoggedIn','Faça login para poder jogar');
            return View::make('stbox.login');
        }

    }

    //Faz uma query à BD para ir buscar os valores e devolve os dados por um array
    public function Matches(){

        //Verifica se o utilizador já fez login
        if(isset($_SESSION['loggedIn'])){

            $user = $_SESSION['id'];

            $db = mysqli_connect('localhost', 'root', '', 'shuthebox');

            $query = "SELECT * FROM matches WHERE idUsername = '$user' ORDER BY data DESC";

            $queryResult = mysqli_query($db,$query);

            $match = new Match();

            //Enquanto a query encontrar dados, atribui os dados a um array
            while ($match = mysqli_fetch_object($queryResult)){
                $matches[] = $match;
            }

            //Se a query não encontrar nenhuns dados que coincidam, devolve a vista das partidas com um aviso
            if(mysqli_num_rows($queryResult) == 0){
                $_SESSION['noMatches'] = 'Este utilizador não tem partidas';
                return View::make('stbox.matches');
            }else{
                //Senão devolve a vista das partidas com os dados encontrados na query
                $_SESSION['numRows'] = mysqli_num_rows($queryResult);
                return View::make('stbox.matches', ['matches' => $matches]);
            }
        }else{
            //Se o utilizador não tiver feito login, devolve uma vista de erro, com um aviso
            $_SESSION['notLoggedIn'] = "É necessário realizar login";
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
        if(/*isset($_SESSION['loggedIn'])*/Session::has('loggedIn')){
            $users = new User();
            return View::make('stbox.profile', ['users'=>$users]);
        }else{
            //Senão a função devolve uma vista de erro com um aviso
            Session::set('notLoggedIn','É necessário realizar login');
            return View::make('stbox.errorNotLoggedIn');
        }
    }
}