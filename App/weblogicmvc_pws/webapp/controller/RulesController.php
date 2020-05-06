<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class RulesController extends BaseController
{

    public function index(){
        return View::make('rules.index');
    }

    public function loginPage () {
        return View::make('login.loginPage');
    }

    public function top10page () {
        return View::make('top10.top10page');
    }

    public function registerPage() {
        return View::make('register.registerpage');
    }

}