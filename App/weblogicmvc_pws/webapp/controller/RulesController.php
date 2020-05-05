<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class RulesController extends BaseController
{

    public function index(){
        return View::make('rules.index');
    }


}