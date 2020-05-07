<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class MatchesController extends BaseController
{

    public function index(){
        return View::make('matches.index');
    }
}