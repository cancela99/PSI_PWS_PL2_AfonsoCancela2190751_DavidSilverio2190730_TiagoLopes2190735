<?php


use ArmoredCore\Controllers\BaseController;
use ArmoredCore\WebObjects\View;

class BackofficeController extends BaseController
{

    public function index(){
        return View::make('backoffice.index');
    }
}