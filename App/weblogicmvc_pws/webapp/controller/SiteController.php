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
        return View::make('stbox.top10');
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