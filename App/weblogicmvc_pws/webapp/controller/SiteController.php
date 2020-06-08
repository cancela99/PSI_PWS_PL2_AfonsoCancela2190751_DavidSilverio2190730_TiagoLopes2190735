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
        return View::make('stbox.gamepage');
    }

    public function Matches(){
        return View::make('stbox.matches');
    }

    /*public function Backoffice(){
        return View::make('stbox.backoffice');
    }*/

    public function Profile(){
        $users = new User();

        $users->primeiro_nome = 'José';
        return View::make('stbox.profile', ['users'=>$users]);
    }

}