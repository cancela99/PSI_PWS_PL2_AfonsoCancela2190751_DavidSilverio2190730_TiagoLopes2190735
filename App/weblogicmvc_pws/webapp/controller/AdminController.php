<?php

use ArmoredCore\Controllers\BaseController;
use ArmoredCore\Interfaces\ResourceControllerInterface;
use ArmoredCore\WebObjects\Post;
use ArmoredCore\WebObjects\Redirect;
use ArmoredCore\WebObjects\URL;
use ArmoredCore\WebObjects\View;

class AdminController extends BaseController{


    public function backoffice(){
        if($_SESSION['admin'] == 1){
            $users = User::all();
            return View::make('stbox.backoffice', ['users' => $users]);
        }else{
            return View::make('stbox.errorNotLoggedIn');
        }
    }

    public function blockUser($id){

        $user = User::find($id);

        $user->bloqueado = 1;
        $user->databloqueado = date("Y/m/d");
        $user->save();

        $this->backoffice();
    }


    public function unblockUser($id){

        $user = User::find($id);

        $user->bloqueado = 0;
        $user->databloqueado = null;
        $user->save();

        $this->backoffice();
    }
}