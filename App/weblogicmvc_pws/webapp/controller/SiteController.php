<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class SiteController extends BaseController
{

    public function Rules(){
        return View::make('stbox.rules');
    }

    public function Login() {
        return View::make('stbox.login');
    }

    public function Top10() {

        $db = mysqli_connect('localhost', 'root', '', 'shuthebox');

        //$query = "SELECT * FROM matches ORDER BY pontuacao ASC";

        $query = "SELECT * FROM matches INNER JOIN users ON matches.idUsername = users.id ORDER BY pontuacao ASC";

        $queryResult = mysqli_query($db,$query);

        $match = new Match();

        while($match = mysqli_fetch_object($queryResult)){
            $top[] = $match;
        }

        return View::make('stbox.top10', ['top10'=>$top]);

    }

    public function Register() {
        return View::make('stbox.register');
    }

    public function Home() {
        return View::make('stbox.homepage');
    }

    public function Game() {
        if(isset($_SESSION['loggedIn'])){
            return View::make('stbox.gamepage');
        }else{
            $_SESSION['notLoggedIn'] = "É necessário realizar login";
            return View::make('stbox.errorNotLoggedIn');
        }

    }

    public function Matches(){
        if(isset($_SESSION['loggedIn'])){
            return View::make('stbox.matches');
        }else{
            $_SESSION['notLoggedIn'] = "É necessário realizar login";
            return View::make('stbox.errorNotLoggedIn');
        }

    }

    public function Erro(){
        return View::make('stbox.errorNotLoggedIn');
    }

    /*public function Backoffice(){
        return View::make('stbox.backoffice');
    }*/

    public function Profile(){

        if(isset($_SESSION['loggedIn'])){
            $users = new User();
            return View::make('stbox.profile', ['users'=>$users]);
        }else{
            $_SESSION['notLoggedIn'] = "É necessário realizar login";
            return View::make('stbox.errorNotLoggedIn');
        }
    }

}