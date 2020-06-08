<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController{


    public function backoffice(){
        $users = User::all();
        View::make('stbox.backoffice', ['users' => $users]);
    }

}