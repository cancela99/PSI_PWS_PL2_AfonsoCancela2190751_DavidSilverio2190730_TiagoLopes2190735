<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class SiteController extends BaseController
{

    public function indexRules(){
        return View::make('rules.index');
    }

    public function indexLoginPage () {
        return View::make('login.index');
    }

    public function indexTop10 () {
        return View::make('top10.index');
    }

    public function indexRegisterPage() {
        return View::make('register.index');
    }

    public function indexHomePage() {
        return View::make('homepage.index');
    }

    public function indexGamePage() {
        return View::make('gamepage.index');
    }

    public function indexMatches(){
        return View::make('matches.index');
    }

    public function indexBackoffice(){
        return View::make('backoffice.index');
    }

}